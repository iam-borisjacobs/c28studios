<?php

namespace Tests\Feature;

use App\Filament\Resources\InquiryResource;
use App\Filament\Resources\InquiryResource\Pages\EditInquiry;
use App\Filament\Resources\InquiryResource\Pages\ListInquiries;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LeadCaptureInquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_html_and_json_inquiry_submissions_persist_and_return_expected_responses(): void
    {
        // 1. Standard HTML Form Submission -> 302 Redirect with flash message
        $htmlResponse = $this->post(route('inquiries.store'), [
            'name' => 'Elena Vance — SaaS Founder',
            'email' => 'elena@vancesaas.io',
            'company' => 'Vance Cloud Ltd',
            'service_interest' => 'DevOps & Cloud Engineering',
            'engagement_mode' => Inquiry::MODE_SPRINT_FIXED,
            'budget_range' => '$1,200 – $2,500',
            'timeline' => 'Immediate (Within 1–2 Weeks)',
            'message' => 'We need our multi-tenant Laravel SaaS containerized with Docker and deployed via GitHub Actions.',
            'website_url' => '',
        ]);

        $htmlResponse->assertRedirect(route('home') . '#consultation');
        $htmlResponse->assertSessionHas('inquiry_submitted');

        $this->assertDatabaseHas('inquiries', [
            'email' => 'elena@vancesaas.io',
            'company' => 'Vance Cloud Ltd',
            'service_interest' => 'DevOps & Cloud Engineering',
            'engagement_mode' => Inquiry::MODE_SPRINT_FIXED,
            'status' => Inquiry::STATUS_NEW,
        ]);

        // 2. JSON / Modal Fetch Submission -> 201 Created
        $jsonResponse = $this->postJson(route('inquiries.store'), [
            'name' => 'Marcus Sterling — CTO',
            'email' => 'marcus@sterlingops.dev',
            'company' => 'Sterling FinTech',
            'service_interest' => 'DevSecOps Integration',
            'engagement_mode' => Inquiry::MODE_MONTHLY_RETAINER,
            'message' => 'Looking for automated Trivy container image scanning and secret management across our staging and production pipelines.',
            'website_url' => '',
        ]);

        $jsonResponse->assertStatus(201)
            ->assertJson([
                'status' => 'logged',
            ]);

        $this->assertSame(2, Inquiry::query()->unread()->count());
        $this->assertSame(2, Inquiry::query()->activePipeline()->count());
    }

    public function test_honeypot_field_rejects_automated_spam_submissions(): void
    {
        $response = $this->postJson(route('inquiries.store'), [
            'name' => 'Spam Bot',
            'email' => 'bot@spammer.example',
            'service_interest' => 'Website Development',
            'message' => 'Automated spam payload attempting to fill every field in the form.',
            'website_url' => 'https://malicious-spam-link.example',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['website_url']);

        $this->assertDatabaseMissing('inquiries', [
            'email' => 'bot@spammer.example',
        ]);
    }

    public function test_validation_fails_cleanly_when_required_fields_are_missing_or_invalid(): void
    {
        $response = $this->postJson(route('inquiries.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'service_interest' => '',
            'message' => 'Too short',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'email',
                'service_interest',
                'message',
            ]);

        $this->assertSame(0, Inquiry::query()->count());
    }

    public function test_rate_limiting_blocks_excessive_inquiry_submissions_with_429(): void
    {
        $payload = [
            'name' => 'Rate Limit Tester',
            'email' => 'ratelimit@c28studios.com',
            'service_interest' => 'Software Development',
            'message' => 'Testing the 5 requests per minute rate limiter on POST /inquiries.',
            'website_url' => '',
        ];

        for ($i = 1; $i <= 5; $i++) {
            $this->postJson(route('inquiries.store'), $payload)->assertStatus(201);
        }

        // 6th request within the same minute window must be throttled with 429 Too Many Requests
        $this->postJson(route('inquiries.store'), $payload)->assertStatus(429);
    }

    public function test_filament_inquiry_resource_enforces_admin_auth_and_supports_triage_workflow(): void
    {
        $inquiry = Inquiry::factory()->create([
            'name' => 'Jordan Hayes — VP Engineering',
            'email' => 'jordan@hayescloud.io',
            'status' => Inquiry::STATUS_NEW,
        ]);

        // Non-admin receives 403 Forbidden
        $nonAdmin = User::factory()->create(['is_admin' => false]);
        $this->actingAs($nonAdmin)
            ->get(InquiryResource::getUrl('index'))
            ->assertStatus(403);

        // Admin can access InquiryResource list, see unread badge, and triage inquiry
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->get(InquiryResource::getUrl('index'))
            ->assertStatus(200)
            ->assertSee('Jordan Hayes');

        $this->assertSame('1', InquiryResource::getNavigationBadge());

        // Test Mark Responded table action
        Livewire::test(ListInquiries::class)
            ->callTableAction('markResponded', $inquiry);

        $inquiry->refresh();
        $this->assertSame(Inquiry::STATUS_RESPONDED, $inquiry->status);
        $this->assertNotNull($inquiry->responded_at);
        $this->assertNull(InquiryResource::getNavigationBadge());

        // Test EditInquiry page updating status and admin_notes
        Livewire::test(EditInquiry::class, ['record' => $inquiry->getRouteKey()])
            ->fillForm([
                'status' => Inquiry::STATUS_IN_REVIEW,
                'admin_notes' => 'Scoped for $2,500 DevOps & DevSecOps Pipeline Sprint. Follow-up call scheduled.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $inquiry->refresh();
        $this->assertSame(Inquiry::STATUS_IN_REVIEW, $inquiry->status);
        $this->assertStringContainsString('DevOps & DevSecOps Pipeline Sprint', (string) $inquiry->admin_notes);
    }
}

