<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Frontend Developer', 'description' => 'React, Vue, Angular development', 'base_hourly_rate' => 60],
            ['name' => 'Backend Developer', 'description' => 'Laravel, Node.js, Python development', 'base_hourly_rate' => 65],
            ['name' => 'Full Stack Developer', 'description' => 'Both frontend and backend', 'base_hourly_rate' => 70],
            ['name' => 'UI/UX Designer', 'description' => 'Interface and experience design', 'base_hourly_rate' => 55],
            ['name' => 'DevOps Engineer', 'description' => 'Infrastructure and deployment', 'base_hourly_rate' => 75],
            ['name' => 'QA Engineer', 'description' => 'Testing and quality assurance', 'base_hourly_rate' => 50],
            ['name' => 'Project Manager', 'description' => 'Project coordination and management', 'base_hourly_rate' => 80],
            ['name' => 'Mobile Developer', 'description' => 'iOS and Android development', 'base_hourly_rate' => 65],
        ];

        foreach ($roles as $role) {
            \App\Models\TeamRole::create($role);
        }
    }
}
