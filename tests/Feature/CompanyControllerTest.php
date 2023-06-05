<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    public function test_index(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('company'));

        $response->assertStatus(200);
        $response->assertViewIs('company');
    }

    public function test_search_companies(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('company.search', ['keyword' => '9']));

        $response->assertStatus(200);
        
        $data = json_decode($response->content());
        $val = count($data->data);
        //DD($val);     // 出力すると、テスト結果が消える
        $this->assertTrue($val > 0);
    }

    public function test_get_name(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('company.getname', ['code' => '9432']));

        $response->assertStatus(200);
    }
}
