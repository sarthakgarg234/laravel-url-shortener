<?php

namespace Tests\Feature;

use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_guest_cannot_create_short_urls(): void
    {
        $this->post('/short-urls', [
            'original_url' => 'https://example.com',
        ])->assertRedirect('/login');
    }

    public function test_guest_cannot_send_invitations(): void
    {
        $this->post('/invitations', [
            'email' => 'member@example.com',
            'role' => 'Member',
        ])->assertRedirect('/login');
    }

    public function test_public_short_url_never_redirects(): void
    {
        $this->get('/s/abc12345')->assertNotFound();
    }
}
