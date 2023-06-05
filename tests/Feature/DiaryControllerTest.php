<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DiaryControllerTest extends TestCase
{
    public function test_index(): void
    {
        $response = $this->get(route('diary'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_index_login_pass_ymd(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('diary', ['ymd' => '2022-01-01']));

        $response->assertStatus(200);
        $response->assertViewIs('diary');

        $data = $response->getOriginalContent()->getData();

        $this->assertEquals('2022-01-01', $data['selected_date']);
    }

    public function test_index_login_pass(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('diary', []));

        $response->assertStatus(200);
        $response->assertViewIs('diary');

        $data = $response->getOriginalContent()->getData();

        $this->assertEquals(date('Y-m-d'), $data['selected_date']);
    }

    public function test_get_diary(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('diary.get', ['date' => '2023-05-16']));

        $response->assertStatus(200);

        // $data = $response->content();
        // //DD($data);

        // $this->assertEquals('2023-05-16', $data['date']);
    }

    public function test_get_diaries(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('diary.gets', ['date' => '2023-05-16']));

        $response->assertStatus(200);
    }

    public function test_save_diary(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->post(route('diary.save', ['date' => '2023-05-16', 'text' => 'hogehoge']));

        $response->assertStatus(200);
    }

    public function test_delete_diary(): void
    {
        $user = User::where('name', 'test')->first();
        $this->actingAs($user);

        $response = $this->get(route('diary.delete', ['date' => '2023-05-16']));

        $response->assertStatus(200);
    }
}
