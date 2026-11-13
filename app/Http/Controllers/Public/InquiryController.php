<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $inquiry = Inquiry::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company' => $validated['company'] ?? null,
            'service_interest' => $validated['service_interest'],
            'engagement_mode' => $validated['engagement_mode'] ?? Inquiry::MODE_SPRINT_FIXED,
            'project_type' => $validated['project_type'] ?? null,
            'budget_range' => $validated['budget_range'] ?? null,
            'timeline' => $validated['timeline'] ?? null,
            'message' => $validated['message'],
            'status' => Inquiry::STATUS_NEW,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'logged',
                'inquiry_id' => $inquiry->id,
                'message' => 'Your engineering inquiry has been logged in the C28 pipeline. We will follow up within 1 business day.',
            ], 201);
        }

        return redirect(route('home') . '#consultation')
            ->with('inquiry_submitted', 'Your engineering inquiry has been logged in the C28 pipeline. We will follow up within 1 business day.');
    }
}

