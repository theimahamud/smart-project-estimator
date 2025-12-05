<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            ['name' => 'North America', 'code' => 'NA', 'cost_multiplier' => 1.5],
            ['name' => 'Western Europe', 'code' => 'WE', 'cost_multiplier' => 1.4],
            ['name' => 'Eastern Europe', 'code' => 'EE', 'cost_multiplier' => 0.8],
            ['name' => 'Asia Pacific', 'code' => 'AP', 'cost_multiplier' => 0.7],
            ['name' => 'South Asia', 'code' => 'SA', 'cost_multiplier' => 0.5],
            ['name' => 'Latin America', 'code' => 'LA', 'cost_multiplier' => 0.6],
            ['name' => 'Middle East', 'code' => 'ME', 'cost_multiplier' => 0.9],
        ];

        foreach ($regions as $region) {
            \App\Models\Region::create($region);
        }
    }
}

