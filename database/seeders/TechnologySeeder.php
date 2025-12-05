<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technologies = [
            ['name' => 'React', 'category' => 'Frontend', 'description' => 'JavaScript library for UI', 'complexity_multiplier' => 1.0],
            ['name' => 'Vue.js', 'category' => 'Frontend', 'description' => 'Progressive JavaScript framework', 'complexity_multiplier' => 1.0],
            ['name' => 'Angular', 'category' => 'Frontend', 'description' => 'TypeScript-based framework', 'complexity_multiplier' => 1.2],
            ['name' => 'Next.js', 'category' => 'Frontend', 'description' => 'React framework with SSR', 'complexity_multiplier' => 1.1],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'description' => 'Utility-first CSS framework', 'complexity_multiplier' => 0.8],

            ['name' => 'Laravel', 'category' => 'Backend', 'description' => 'PHP web framework', 'complexity_multiplier' => 1.0],
            ['name' => 'Node.js', 'category' => 'Backend', 'description' => 'JavaScript runtime', 'complexity_multiplier' => 1.0],
            ['name' => 'Django', 'category' => 'Backend', 'description' => 'Python web framework', 'complexity_multiplier' => 1.1],
            ['name' => 'Express.js', 'category' => 'Backend', 'description' => 'Node.js web framework', 'complexity_multiplier' => 0.9],

            ['name' => 'MySQL', 'category' => 'Database', 'description' => 'Relational database', 'complexity_multiplier' => 0.9],
            ['name' => 'PostgreSQL', 'category' => 'Database', 'description' => 'Advanced relational database', 'complexity_multiplier' => 1.0],
            ['name' => 'MongoDB', 'category' => 'Database', 'description' => 'NoSQL document database', 'complexity_multiplier' => 1.0],
            ['name' => 'Redis', 'category' => 'Database', 'description' => 'In-memory data store', 'complexity_multiplier' => 0.8],

            ['name' => 'Docker', 'category' => 'DevOps', 'description' => 'Containerization platform', 'complexity_multiplier' => 1.1],
            ['name' => 'Kubernetes', 'category' => 'DevOps', 'description' => 'Container orchestration', 'complexity_multiplier' => 1.5],
            ['name' => 'GitHub Actions', 'category' => 'DevOps', 'description' => 'CI/CD automation', 'complexity_multiplier' => 0.9],

            ['name' => 'OpenAI API', 'category' => 'AI/ML', 'description' => 'AI language models', 'complexity_multiplier' => 1.3],
            ['name' => 'TensorFlow', 'category' => 'AI/ML', 'description' => 'Machine learning framework', 'complexity_multiplier' => 1.8],
            ['name' => 'LangChain', 'category' => 'AI/ML', 'description' => 'LLM application framework', 'complexity_multiplier' => 1.4],
        ];

        foreach ($technologies as $tech) {
            \App\Models\Technology::create($tech);
        }
    }
}
