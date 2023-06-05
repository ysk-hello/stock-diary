<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyDiaryControllerTest extends TestCase
{
    public function test_index(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('companydiary'));

        $response->assertStatus(200);
        $response->assertViewIs('companydiary');
    }

    public function test_get_companydiary(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('companydiary.get', ['company_code' => 9433,'date' => '2023-05-18']));

        $response->assertStatus(200);

        // $data = $response->content();
        // DD($data);

        // $this->assertEquals('2023-05-16', $data['date']);
    }
    
    public function test_get_companydiaries(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('companydiary.gets', ['company_code' => 9433, 'date' => '2023-05-16']));

        $response->assertStatus(200);

        // $data = $response->content();
        // DD($data);

        // $this->assertEquals('2023-05-16', $data['date']);
    }

    public function test_save_companydiary(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->post(route('companydiary.save', ['company_code' => 9433, 'date' => '2023-05-16', 'text' => 'hogehoge']));

        $response->assertStatus(200);
    }

    public function test_delete_companydiary(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('companydiary.delete', ['company_code' => 9433, 'date' => '2023-05-16']));

        $response->assertStatus(200);
    }
}
