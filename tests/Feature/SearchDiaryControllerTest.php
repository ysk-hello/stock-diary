<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchDiaryControllerTest extends TestCase
{
    public function test_index(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('search'));

        $response->assertStatus(200);
        $response->assertViewIs('search');
    }

    public function test_search_diaries(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('search.diary', ['keyword' => 'test']));

        $response->assertStatus(200);
        
        $data = json_decode($response->content());
        $val = count($data->data);
        //DD($data);     // 出力すると、テスト結果が消える
        $this->assertTrue($val > 0);
    }

    public function test_search_companydiaries(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('search.companydiary', ['keyword' => 'test']));

        $response->assertStatus(200);
        
        $data = json_decode($response->content());
        $val = count($data->data);
        //DD($data);     // 出力すると、テスト結果が消える
        $this->assertTrue($val > 0);
    }
}
