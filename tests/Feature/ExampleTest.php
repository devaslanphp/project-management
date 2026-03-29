<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_root_route_redirects_guests_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('filament.app.auth.login'));
    }

    public function test_the_login_page_can_be_rendered_without_a_database_connection(): void
    {
        $response = $this->get(route('filament.app.auth.login'));

        $response->assertOk();
    }
}
