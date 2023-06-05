<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'code' => 9432,
                'name' => 'NTT'
            ],
            [
                'code' => 9433,
                'name' => 'KDDI'
            ],
            [
                'code' => 9436,
                'name' => '沖縄セルラー'
            ]
        ];

        foreach($companies as $comany) {
            \App\Models\Company::create($comany);
        }
    }
}
