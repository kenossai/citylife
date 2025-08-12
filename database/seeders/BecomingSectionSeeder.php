<?php

namespace Database\Seeders;

use App\Models\BecomingSection;
use Illuminate\Database\Seeder;

class BecomingSectionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        BecomingSection::create([
            'tagline' => 'Are You Ready to Make a Difference?',
            'title' => 'Inspiring and Helping for Better',
            'title_highlight' => 'Lifestyle',
            'description' => 'Join our community in making a positive impact in the lives of others. Through faith, service, and fellowship, we work together to build a stronger, more compassionate society where everyone can thrive and grow in their spiritual journey.',
            'volunteer_title' => 'Become A Volunteer',
            'volunteer_icon' => 'icon-unity',
            'new_member_title' => "I'm New Here",
            'new_member_icon' => 'icon-healthcare',
            'is_active' => true,
        ]);
    }
}
