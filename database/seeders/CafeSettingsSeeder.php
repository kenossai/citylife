<?php

namespace Database\Seeders;

use App\Models\CafeSetting;
use Illuminate\Database\Seeder;

class CafeSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'cafe_name',
                'name' => 'Cafe Name',
                'value' => 'CityLife Cafe',
                'type' => 'string',
                'group' => 'general',
                'description' => 'The name of the cafe',
                'is_public' => true,
            ],
            [
                'key' => 'cafe_description',
                'name' => 'Cafe Description',
                'value' => 'A warm and welcoming place to enjoy great food and fellowship',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Brief description of the cafe',
                'is_public' => true,
            ],
            [
                'key' => 'cafe_phone',
                'name' => 'Cafe Phone Number',
                'value' => '+44 20 1234 5678',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Contact phone number',
                'is_public' => true,
            ],
            [
                'key' => 'cafe_email',
                'name' => 'Cafe Email',
                'value' => 'cafe@citylifechurch.org',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Contact email address',
                'is_public' => true,
            ],

            // Opening Hours
            [
                'key' => 'opening_hours_monday',
                'name' => 'Monday Opening Hours',
                'value' => '8:00 AM - 4:00 PM',
                'type' => 'string',
                'group' => 'hours',
                'description' => 'Opening hours for Monday',
                'is_public' => true,
            ],
            [
                'key' => 'opening_hours_tuesday',
                'name' => 'Tuesday Opening Hours',
                'value' => '8:00 AM - 4:00 PM',
                'type' => 'string',
                'group' => 'hours',
                'description' => 'Opening hours for Tuesday',
                'is_public' => true,
            ],
            [
                'key' => 'opening_hours_wednesday',
                'name' => 'Wednesday Opening Hours',
                'value' => '8:00 AM - 4:00 PM',
                'type' => 'string',
                'group' => 'hours',
                'description' => 'Opening hours for Wednesday',
                'is_public' => true,
            ],
            [
                'key' => 'opening_hours_thursday',
                'name' => 'Thursday Opening Hours',
                'value' => '8:00 AM - 4:00 PM',
                'type' => 'string',
                'group' => 'hours',
                'description' => 'Opening hours for Thursday',
                'is_public' => true,
            ],
            [
                'key' => 'opening_hours_friday',
                'name' => 'Friday Opening Hours',
                'value' => '8:00 AM - 4:00 PM',
                'type' => 'string',
                'group' => 'hours',
                'description' => 'Opening hours for Friday',
                'is_public' => true,
            ],
            [
                'key' => 'opening_hours_saturday',
                'name' => 'Saturday Opening Hours',
                'value' => '9:00 AM - 3:00 PM',
                'type' => 'string',
                'group' => 'hours',
                'description' => 'Opening hours for Saturday',
                'is_public' => true,
            ],
            [
                'key' => 'opening_hours_sunday',
                'name' => 'Sunday Opening Hours',
                'value' => 'Closed',
                'type' => 'string',
                'group' => 'hours',
                'description' => 'Opening hours for Sunday',
                'is_public' => true,
            ],

            // Payment Settings
            [
                'key' => 'tax_rate',
                'name' => 'Tax Rate (%)',
                'value' => '20',
                'type' => 'number',
                'group' => 'payment',
                'description' => 'VAT rate percentage',
                'is_public' => false,
            ],
            [
                'key' => 'accept_cash',
                'name' => 'Accept Cash Payments',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Whether to accept cash payments',
                'is_public' => true,
            ],
            [
                'key' => 'accept_card',
                'name' => 'Accept Card Payments',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Whether to accept card payments',
                'is_public' => true,
            ],
            [
                'key' => 'minimum_card_amount',
                'name' => 'Minimum Card Amount',
                'value' => '5.00',
                'type' => 'number',
                'group' => 'payment',
                'description' => 'Minimum amount for card payments',
                'is_public' => true,
            ],

            // Order Settings
            [
                'key' => 'allow_online_ordering',
                'name' => 'Allow Online Ordering',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'orders',
                'description' => 'Whether to allow online orders',
                'is_public' => true,
            ],
            [
                'key' => 'order_ahead_time',
                'name' => 'Order Ahead Time (minutes)',
                'value' => '15',
                'type' => 'number',
                'group' => 'orders',
                'description' => 'Minimum time required for order preparation',
                'is_public' => true,
            ],
            [
                'key' => 'max_daily_orders',
                'name' => 'Maximum Daily Orders',
                'value' => '100',
                'type' => 'number',
                'group' => 'orders',
                'description' => 'Maximum number of orders per day',
                'is_public' => false,
            ],

            // Notifications
            [
                'key' => 'notification_email',
                'name' => 'Notification Email',
                'value' => 'cafe@citylifechurch.org',
                'type' => 'string',
                'group' => 'notifications',
                'description' => 'Email for order notifications',
                'is_public' => false,
            ],
            [
                'key' => 'send_order_confirmations',
                'name' => 'Send Order Confirmations',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Whether to send order confirmation emails',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            CafeSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
