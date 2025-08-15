<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TechnicalDepartment;

class TechnicalDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'PA',
                'slug' => 'pa',
                'description' => 'Public Address system management - responsible for sound engineering, microphones, and audio equipment during services and events.',
                'requirements' => 'Basic understanding of audio equipment, willingness to learn, and commitment to serve during services.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Media',
                'slug' => 'media',
                'description' => 'Media production and management - handles video recording, live streaming, social media content, and multimedia presentations.',
                'requirements' => 'Interest in media production, basic computer skills, and creative mindset for content creation.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Visual',
                'slug' => 'visual',
                'description' => 'Visual presentation and lighting - manages projection systems, lighting design, stage setup, and visual displays during services.',
                'requirements' => 'Eye for design, basic technical skills, and ability to work with presentation software and lighting equipment.',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            TechnicalDepartment::updateOrCreate(
                ['slug' => $department['slug']],
                $department
            );
        }
    }
}
