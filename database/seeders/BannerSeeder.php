<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create banners based on existing static content
        Banner::create([
            'title' => 'We Don\'t Believe in Prayer',
            'subtitle' => 'Pass It On',
            'description' => 'We Believe in Answered Prayer',
            'background_image' => 'assets/images/backgrounds/slider-1-2.jpeg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Banner::create([
            'title' => 'We\'re a vibrant, Spirit-filled, Bible-believing',
            'subtitle' => 'Give a helping hand for a child',
            'description' => 'church in the heart of Sheffield',
            'background_image' => 'assets/images/backgrounds/worship-image.jpg',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Banner::create([
            'title' => 'lend a helping hand',
            'subtitle' => 'Give a helping hand for a child',
            'description' => 'to who those need it',
            'background_image' => 'assets/images/backgrounds/slider-1-3.jpg',
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}
