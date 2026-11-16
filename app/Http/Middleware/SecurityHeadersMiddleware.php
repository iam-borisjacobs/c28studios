<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Known exploit scanners, SQLi automated tools, and aggressive vulnerability harvesters.
     * Legitimate search engines (Googlebot, Bingbot, etc.) and AI crawlers (GPTBot, OAI-SearchBot,
     * ChatGPT-User, ClaudeBot, PerplexityBot, Google-Extended) are explicitly allowed.
     *
     * @var array<int, string>
     */
    private const BLOCKED_USER_AGENTS = [
        'sqlmap',
        'nikto',
        'acunetix',
        'nessus',
        'nuclei',
        'masscan',
        'zgrab',
        'dirbuster',
        'gobuster',
        'wfuzz',
        'havij',
        'morfeus',
        'jaeles',
        'commix',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = strtolower((string) $request->userAgent());

        if ($userAgent !== '') {
            foreach (self::BLOCKED_USER_AGENTS as $signature) {
                if (str_contains($userAgent, $signature)) {
                    abort(403, 'Automated vulnerability scanning is prohibited.');
                }
            }
        }

        /** @var Response $response */
        $response = $next($request);

        // Remove server fingerprinting headers
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }
        $response->headers->remove('X-Powered-By');

        // Core Anti-Injection & Clickjacking Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        // Anti-Tracking & Privacy Headers
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-DNS-Prefetch-Control', 'off');
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=(), browsing-topics=(), join-ad-interest-group=(), run-ad-auction=()'
        );

        // Strict Content-Security-Policy for public routes (leave /admin and /livewire flexible for Filament)
        if (! $request->is('admin*') && ! $request->is('livewire*')) {
            $csp = implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline'",
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
                "font-src 'self' https://fonts.gstatic.com data:",
                "img-src 'self' data: blob: https:",
                "connect-src 'self'",
                "media-src 'self'",
                "object-src 'none'",
                "base-uri 'self'",
                "frame-ancestors 'self'",
                "form-action 'self'",
            ]);
            $response->headers->set('Content-Security-Policy', $csp);

            // Prevent search engines from indexing admin or form submission endpoints, while allowing rich indexing on public pages
            if (! $response->headers->has('X-Robots-Tag') && $request->isMethod('GET')) {
                $response->headers->set('X-Robots-Tag', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1');
            }
        } else {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=63072000; includeSubDomains; preload'
            );
        }

        return $response;
    }
}
