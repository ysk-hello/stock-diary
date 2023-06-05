<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diaries = [
            [
                'date' => '2023-05-18',
                'text' => 'hogehoge',
                'user_id' => 1
            ],
            [
                'date' => '2023-05-17',
                'text' => 'testtest',
                'user_id' => 1
            ]
        ];

        foreach($diaries as $diary) {
            \App\Models\Diary::create($diary);
        }
    }
}
