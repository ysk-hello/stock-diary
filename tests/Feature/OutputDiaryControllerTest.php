<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OutputDiaryControllerTest extends TestCase
{
    public function test_index(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('output'));

        $response->assertStatus(200);
        $response->assertViewIs('output');
    }

    public function test_output_diaries(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('output.diary', ['startDate' => '2023-05-10', 'endDate' => '2023-05-16']));

        $response->assertStatus(200);
    }

    public function test_output_companydiaries(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('output.companydiary', ['startDate' => '2023-05-10', 'endDate' => '2023-05-16']));

        $response->assertStatus(200);
    }
}
