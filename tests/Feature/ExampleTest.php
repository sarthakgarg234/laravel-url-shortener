<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_short_urls_are_not_publicly_resolvable(): void
    {
        $response = $this->get('/s/testcode');

        $response->assertNotFound();
    }
}