<?php

namespace Database\Seeders;

use App\Models\CityLifeMusic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CityLifeMusicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $musicData = [
            [
                'title' => 'Amazing Grace',
                'artist' => 'CityLife Worship Team',
                'album' => 'Songs of Worship',
                'genre' => 'Contemporary Christian',
                'description' => 'A beautiful rendition of the classic hymn Amazing Grace, bringing hope and redemption through powerful vocals and modern arrangement.',
                'is_published' => true,
                'is_featured' => true,
                'spotify_url' => 'https://open.spotify.com/track/example1',
                'apple_music_url' => 'https://music.apple.com/us/album/example1',
                'youtube_url' => 'https://www.youtube.com/watch?v=example1',
            ],
            [
                'title' => 'How Great Thou Art',
                'artist' => 'CityLife Choir',
                'album' => 'Classic Hymns',
                'genre' => 'Hymn',
                'description' => 'A majestic performance of this beloved hymn that declares God\'s greatness and sovereignty over all creation.',
                'is_published' => true,
                'is_featured' => true,
                'spotify_url' => 'https://open.spotify.com/track/example2',
                'youtube_url' => 'https://www.youtube.com/watch?v=example2',
            ],
            [
                'title' => 'Way Maker',
                'artist' => 'CityLife Worship',
                'album' => 'Miracles',
                'genre' => 'Contemporary Worship',
                'description' => 'An uplifting song about God\'s ability to make a way where there seems to be no way, perfect for times of uncertainty.',
                'is_published' => true,
                'is_featured' => false,
                'spotify_url' => 'https://open.spotify.com/track/example3',
                'apple_music_url' => 'https://music.apple.com/us/album/example3',
                'youtube_url' => 'https://www.youtube.com/watch?v=example3',
            ],
            [
                'title' => 'Blessed Be Your Name',
                'artist' => 'CityLife Band',
                'album' => 'Praise & Worship',
                'genre' => 'Contemporary Christian',
                'description' => 'A powerful song of worship that encourages believers to praise God in all circumstances, both good and difficult.',
                'is_published' => true,
                'is_featured' => false,
                'spotify_url' => 'https://open.spotify.com/track/example4',
                'youtube_url' => 'https://www.youtube.com/watch?v=example4',
            ],
            [
                'title' => 'What a Beautiful Name',
                'artist' => 'CityLife Worship Team',
                'album' => 'Jesus',
                'genre' => 'Contemporary Worship',
                'description' => 'A modern worship anthem celebrating the beautiful name of Jesus and His power to save and transform lives.',
                'is_published' => true,
                'is_featured' => true,
                'apple_music_url' => 'https://music.apple.com/us/album/example5',
                'youtube_url' => 'https://www.youtube.com/watch?v=example5',
            ],
            [
                'title' => 'Great Are You Lord',
                'artist' => 'CityLife Collective',
                'album' => 'One Thing Remains',
                'genre' => 'Contemporary Worship',
                'description' => 'A heartfelt song of adoration declaring God\'s greatness and our response to His amazing love and faithfulness.',
                'is_published' => true,
                'is_featured' => false,
                'spotify_url' => 'https://open.spotify.com/track/example6',
                'youtube_url' => 'https://www.youtube.com/watch?v=example6',
            ],
            [
                'title' => 'Holy Spirit',
                'artist' => 'CityLife Worship',
                'album' => 'Spirit & Truth',
                'genre' => 'Contemporary Christian',
                'description' => 'An intimate worship song inviting the Holy Spirit to move and transform hearts during times of worship and prayer.',
                'is_published' => true,
                'is_featured' => false,
                'spotify_url' => 'https://open.spotify.com/track/example7',
                'apple_music_url' => 'https://music.apple.com/us/album/example7',
            ],
            [
                'title' => 'Be Still My Soul',
                'artist' => 'CityLife Acoustic',
                'album' => 'Quiet Reflections',
                'genre' => 'Hymn',
                'description' => 'A peaceful, acoustic arrangement of this classic hymn that brings comfort and reminds us to trust in God\'s perfect timing.',
                'is_published' => true,
                'is_featured' => false,
                'youtube_url' => 'https://www.youtube.com/watch?v=example8',
            ],
            [
                'title' => 'Good Good Father',
                'artist' => 'CityLife Youth',
                'album' => 'Father\'s Love',
                'genre' => 'Contemporary Christian',
                'description' => 'A tender song about God\'s perfect love as our Heavenly Father, reminding us of His goodness and care for His children.',
                'is_published' => true,
                'is_featured' => true,
                'spotify_url' => 'https://open.spotify.com/track/example9',
                'apple_music_url' => 'https://music.apple.com/us/album/example9',
                'youtube_url' => 'https://www.youtube.com/watch?v=example9',
            ],
            [
                'title' => 'Cornerstone',
                'artist' => 'CityLife Band',
                'album' => 'Foundation',
                'genre' => 'Contemporary Worship',
                'description' => 'A powerful declaration of Jesus as our cornerstone and foundation, combining classic hymn lyrics with modern worship style.',
                'is_published' => false,
                'is_featured' => false,
                'spotify_url' => 'https://open.spotify.com/track/example10',
                'youtube_url' => 'https://www.youtube.com/watch?v=example10',
            ],
        ];

        foreach ($musicData as $music) {
            CityLifeMusic::create([
                'title' => $music['title'],
                'slug' => Str::slug($music['title']),
                'artist' => $music['artist'],
                'album' => $music['album'],
                'genre' => $music['genre'],
                'description' => $music['description'],
                'is_published' => $music['is_published'],
                'is_featured' => $music['is_featured'],
                'spotify_url' => $music['spotify_url'] ?? null,
                'apple_music_url' => $music['apple_music_url'] ?? null,
                'youtube_url' => $music['youtube_url'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('CityLife Music seeded successfully!');
    }
}
