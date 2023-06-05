<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoutingTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    public function test_index_fail(): void
    {
        $user = User::where('name', 'guest')->first();
        $this->actingAs($user);

        $response = $this->get(route('upload'));

        $response->assertStatus(404);
    }

    public function test_index_pass(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('upload'));

        $response->assertStatus(200);
        $response->assertViewIs('upload');
    }
}
