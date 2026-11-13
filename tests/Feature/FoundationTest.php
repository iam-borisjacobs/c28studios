<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_homepage_returns_200_and_renders_c28_core_statement(): void
    {
        $response = $this->withoutVite()->get('/');

        $response->assertStatus(200);
        $response->assertSee('C28 — Software & Cloud Engineering', false);
        $response->assertSee('We build digital products and help teams deploy, secure, and operate them reliably.');
        $response->assertSee('DevOps & Cloud Engineering', false);
        $response->assertSee('DevSecOps');
        $response->assertSee('Software Development');
        $response->assertSee('Website Development');
    }

    public function test_filament_admin_login_page_is_accessible(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_is_redirected_from_admin_panel(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_non_admin_user_is_forbidden_from_admin_panel(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_authenticated_admin_user_can_access_admin_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }
}
