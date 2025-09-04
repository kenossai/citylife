<?php

namespace Database\Seeders;

use App\Models\Mission;
use Illuminate\Database\Seeder;

class MissionSeeder extends Seeder
{
    public function run(): void
    {
        $missions = [
            [
                'title' => 'Food & Toiletries Packages',
                'slug' => 'food-toiletries-packages',
                'description' => 'Collecting and distributing essential items to families in need within our local community.',
                'content' => "We collect non-perishable food items and toiletries to distribute to people who need them. This program runs throughout the year, with special collections during holiday seasons.\n\nItems needed include:\n• Canned goods\n• Pasta and rice\n• Breakfast cereals\n• Toiletries (toothpaste, soap, shampoo)\n• Baby products\n• Household cleaning supplies\n\nIf you wish to donate, please bring your items and place them in the designated baskets in the main hall.",
                'location' => 'Sheffield, UK',
                'target_group' => 'Families in need',
                'mission_type' => 'home',
                'contact_person' => 'Pastor Terence and Vivienne Williams',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'City Life Kids & Families Foundation',
                'slug' => 'citylife-kids-families-foundation',
                'description' => 'Supporting disadvantaged children through meals during school holidays and uniform events.',
                'content' => "The City Life Kids Foundation is set up to help disadvantaged children through various initiatives including:\n\n• Providing meals during school holidays\n• Hosting pre-loved uniform events\n• Educational support programs\n• Holiday activities and clubs\n• Family support services\n\nOur foundation works closely with local schools and community organizations to identify children and families who would benefit from our support.",
                'location' => 'Sheffield, UK',
                'target_group' => 'Disadvantaged children and families',
                'mission_type' => 'home',
                'contact_person' => 'Foundation Team',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Pre-Loved School Uniform',
                'slug' => 'pre-loved-school-uniform',
                'description' => 'Free school uniform distribution events for families struggling with uniform costs.',
                'content' => "We collect second hand school uniform items to distribute at our 'Pre-Loved School Uniform' events, where families who are struggling with the cost of school uniform can come and get the items they need free of charge.\n\nWe accept donations of:\n• School shirts and blouses\n• Trousers and skirts\n• Jumpers and cardigans\n• PE kits and sports wear\n• Shoes and accessories\n\nAll items should be clean and in good condition. Events are typically held before the start of each school term.\n\nDetails of upcoming Pre-Loved School Uniform events can be found on our events page.",
                'location' => 'Sheffield, UK',
                'target_group' => 'School children and families',
                'mission_type' => 'home',
                'contact_person' => 'Uniform Team',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'title' => 'The John Project',
                'slug' => 'john-project-india',
                'description' => 'Transforming lives of children and women in India through education, care, and skills training.',
                'content' => "The John Foundation was established in 2007 with the goal of transforming lives and bringing hope. In June 2007, a pastor saw a father leaving two girls on a road near Hyderabad. When he confronted him, the father told him that he no longer had the resources to provide for his five children.\n\nWhat began with two kids in June 2007 has since grown and today we run:\n\n• 24 children's homes where 262 kids receive a safe home, loving care and mentoring\n• 23 tuition centres where 690 children receive after school academic help\n• 650 children of widows and single mothers kept in school through monthly financial support\n• John's Academy School providing education for close to 300 kids\n• An Employable Skills Training Program: Over 8000 trained so far\n• Asha Restoration Homes for young girls: 200+ have gone through our rescue and rehabilitation program\n• Two homes for HIV/AIDS children and young girls\n\nThe John Foundation continues to grow and expand its reach, providing hope and opportunity to thousands of children and families.",
                'location' => 'Hyderabad, India',
                'target_group' => 'Children, women, and families at risk',
                'mission_type' => 'abroad',
                'contact_person' => 'Saji and Cynthia John',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Shalom Project',
                'slug' => 'shalom-project-delhi',
                'description' => 'Feeding and educating children in one of India\'s largest slums in New Delhi.',
                'content' => "Mayapuri Slum is one of the largest slums in India and is situated in the capital city of New Delhi. The Shalom Project aims to feed children living in the slum and provide them with an education that will help them towards a better future.\n\nThe project includes:\n• Daily meal programs for children\n• Educational classes and tutoring\n• Library and learning resources\n• Health and hygiene education\n• Skills development for older children\n• Community outreach programs\n\nThe Shalom Project is led by Pastor Das, who has been working in the slum community for many years, building relationships and trust with the families.\n\nOur goal is to break the cycle of poverty through education and care, giving these children hope for a better future.",
                'location' => 'New Delhi, India',
                'target_group' => 'Children in slum communities',
                'mission_type' => 'abroad',
                'contact_person' => 'Pastor Das',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'DRC Community Development',
                'slug' => 'drc-community-development',
                'description' => 'Building self-sufficient communities in the Democratic Republic of Congo.',
                'content' => "Our representatives for this project are City Life members Jacques and Liliane Kalenga, who are originally from the DRC. We are affiliated with 'La Vie Abondante' Church in the Kasai Oriental province in Mbuji Mayi town.\n\nLa Vie Abondante is run by Senior Pastor Kabundji Tshitenda Hilaire and his wife Georgette.\n\nThe objective of this project is to make the local community self-sufficient. We aim to achieve this by helping them to:\n• Acquire their own land for farming\n• Build a church facility\n• Establish a health centre\n• Create a school for the community\n• Develop sustainable income sources\n\nRecent achievements include:\n• Purchased a generator for La Vie Abondante so they can stop hiring electricity from other sources\n• Established farming cooperatives\n• Provided medical supplies and equipment\n• Supported teacher training programs\n\nThank you for your support! Merci pour votre soutien!",
                'location' => 'Mbuji Mayi, Democratic Republic of Congo',
                'target_group' => 'Rural communities',
                'mission_type' => 'abroad',
                'contact_person' => 'Jacques and Liliane Kalenga',
                'contact_email' => 'admin1@citylifecc.com',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($missions as $missionData) {
            Mission::updateOrCreate(
                ['slug' => $missionData['slug']],
                $missionData
            );
        }
    }
}
