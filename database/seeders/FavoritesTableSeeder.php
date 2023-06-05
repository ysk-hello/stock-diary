<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $favorites = [
            [
                'company_code' => 9432,
                'user_id' => 1
            ],
            [
                'company_code' => 9433,
                'user_id' => 1
            ]
        ];

        foreach($favorites as $favorite) {
            \App\Models\Favorite::create($favorite);
        }
    }
}
