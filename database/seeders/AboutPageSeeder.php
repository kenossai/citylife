<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutPage;
use App\Models\CoreValue;

class AboutPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main about page
        $aboutPage = AboutPage::create([
            'title' => 'About Us',
            'introduction' => 'City Life is a vibrant spirit filled multi-cultural church affiliated with the Assemblies of God. We are situated at the heart of Kelham Island which is known for its heritage linked to steel industries.',
            'church_name' => 'City Life',
            'church_description' => 'A vibrant spirit filled multi-cultural church',
            'affiliation' => 'Assemblies of God',
            'location_description' => 'Heart of Kelham Island',
            'meta_title' => 'About City Life - Multi-cultural Church in Kelham Island',
            'meta_description' => 'Learn about City Life, a vibrant spirit-filled multi-cultural church affiliated with the Assemblies of God, located in the heart of Kelham Island.',
            'meta_keywords' => ['church', 'multi-cultural', 'Assemblies of God', 'Kelham Island', 'Sheffield', 'Christian community'],
            'social_media_links' => [
                'youtube' => 'https://www.youtube.com/channel/UCTP2_DfFmZfg5ooFu6alMvA',
                'facebook' => 'https://www.facebook.com/drjimaster',
                'twitter' => null,
                'instagram' => null,
            ],
            'phone_number' => '0114 272 8243',
            'email_address' => 'info@citylifecc.com',
            'address' => 'Kelham Island, Sheffield',
            'is_active' => true,
            'sort_order' => 1,
            'slug' => 'about-us',
        ]);

        // Create the 7 core values
        $coreValues = [
            [
                'title' => 'Care',
                'slug' => 'care',
                'description' => 'As Christians, we are to be a reflection of God on earth, caring for and serving others. We are a family of Christians who should care for people, helping whenever possible and pointing people in the right direction. We support and pray for every member and direct them to areas where there is a specific need.',
                'short_description' => 'Caring for and serving others as a reflection of God on earth.',
                'bible_verse' => 'For God is not unjust. He will not forget how hard you have worked for Him and how you have shown your love to Him by caring for other believers, as you still do. Our great desire is that you will keep on loving others as long as life lasts, in order to make certain that what you hope for will come true.',
                'bible_reference' => 'Hebrews 6:10-12',
                'icon' => 'icon-heart',
                'featured_image' => 'core-values/care.jpg',
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Communication',
                'slug' => 'communication',
                'description' => 'Communicating God\'s love is vital. We are a praying church that continually seeks God for direction, holding prayer meetings throughout the week. From a practical perspective, we communicate to all members of our church through email, text and fellowship.',
                'short_description' => 'Communicating God\'s love through prayer and fellowship.',
                'bible_verse' => 'Rather, speaking the truth in love, we are to grow up in every way into Him who is the head, into Christ',
                'bible_reference' => 'Ephesians 4:15',
                'icon' => 'icon-communication',
                'featured_image' => 'core-values/communication.jpg',
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Culture',
                'slug' => 'culture',
                'description' => 'In order to build a "Christian culture", we must first recognise servanthood within the Church. We are to be the greatest servants, allowing others to go before us and developing a culture of humility, kindness, understanding, love and flexibility. Understanding the bigger picture will help us to build up the people around us, instead of fulfilling our own ambitions.',
                'short_description' => 'Building a Christian culture of servanthood and humility.',
                'bible_verse' => 'Do nothing out of selfish ambition or vain conceit. Rather, in humility value others above yourselves, not looking to your own interests, but each of you to the interests of the others. In your relationships with one another, have the same mindset as Christ Jesus.',
                'bible_reference' => 'Philippians 2:3',
                'icon' => 'icon-globe',
                'featured_image' => 'core-values/culture.jpg',
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Coaching',
                'slug' => 'coaching',
                'description' => 'We are here to develop newly born Christians and existing members to the highest standard with the word of God, giving everyone a chance to understand the scriptures. We are called to make disciples and to develop members into leaders, empowering them to use their gifts. We are to be the reflection of Christ to those we are coaching. We develop new converts by running courses such as our Bible school, Christian Development course and Living a Christian Life course.',
                'short_description' => 'Developing Christians to the highest standard with God\'s word.',
                'bible_verse' => 'Each of you should use whatever gift you have received to serve others, as faithful stewards of God\'s grace in various forms.',
                'bible_reference' => '1 Peter 4:10',
                'icon' => 'icon-book',
                'featured_image' => 'core-values/coaching.jpg',
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Community',
                'slug' => 'community',
                'description' => 'The idea of community comes from the sense of responsibility we have towards each other. In the Bible, God encourages us to take care of our brethren while following the word of the Lord. We reach out to our community with different events and programmes, such as international food night, flower arranging sessions, distributing pre-loved school uniforms and other types of social events that are all designed to help reach our goals.',
                'short_description' => 'Taking responsibility for each other and reaching out to our community.',
                'bible_verse' => 'And when did we see you sick or in prison and visit you? And the King will answer them, \'Truly, I say to you, as you did it to one of the least of these my brothers, you did it to me.\'',
                'bible_reference' => 'Matthew 25:39',
                'icon' => 'icon-group',
                'featured_image' => 'core-values/community.jpg',
                'sort_order' => 5,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Commission (abroad)',
                'slug' => 'commission-abroad',
                'description' => 'We are mission-minded and all nationalities are welcome in the church. We use our existing involvement with India and Kenya, taking members with us on mission and supporting teaching, helping to run a school and reaching villages and tribes. We also have a heart to send help to those already serving in those areas.',
                'short_description' => 'Mission-minded church supporting work in India and Kenya.',
                'bible_verse' => 'Also I heard the voice of the Lord, saying, Whom shall I send, and who will go for us? Then said I, Here am I; send me.',
                'bible_reference' => 'Isaiah 6:8',
                'icon' => 'icon-plane',
                'featured_image' => 'core-values/commission.jpg',
                'sort_order' => 6,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Consistency and commitment',
                'slug' => 'consistency-and-commitment',
                'description' => 'We believe that every member and leader within the church should be consistent and committed. This means good time management, diligence, honouring others, being respectful, being teachable, having no personal agenda and walking with the Lord. Every ministry is to be built on consistency and commitment towards one another.',
                'short_description' => 'Every member should be consistent and committed to walking with the Lord.',
                'bible_verse' => 'And they devoted themselves to the apostles\' teaching and the fellowship, to the breaking of bread and the prayers.',
                'bible_reference' => 'Acts 2:42',
                'icon' => 'icon-consistency',
                'featured_image' => 'core-values/consistency.jpg',
                'sort_order' => 7,
                'is_active' => true,
                'is_featured' => true,
            ],
        ];

        // Create core values linked to the about page
        foreach ($coreValues as $valueData) {
            $valueData['about_page_id'] = $aboutPage->id;
            CoreValue::create($valueData);
        }

        $this->command->info('About page and core values created successfully!');
    }
}
