<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    public function test_index(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('favorite'));

        $response->assertStatus(200);
        $response->assertViewIs('favorite');
    }

    public function test_get_favorites(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('favorite.gets', ['date' => '2023-05-19']));

        $response->assertStatus(200);

        $data = json_decode($response->content());
        $val = count($data);
        //DD($data);     // 出力すると、テスト結果が消える
        $this->assertTrue($val > 0);
    }

    public function test_is_favorite(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('favorite.check', ['code' => '9432']));

        $response->assertStatus(200);

        //DD($response['state']);
        $this->assertTrue($response['state']);
    }

    public function test_toggle_favorite(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('favorite.toggle', ['code' => '9436']));

        $response->assertStatus(200);

        //DD($response['isFavorite']);
        //$this->assertTrue($response['state']);
    }
}
