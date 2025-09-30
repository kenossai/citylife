<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEOSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_description',
        'default_keywords',
        'google_analytics_id',
        'google_search_console_id',
        'facebook_app_id',
        'twitter_handle',
        'default_og_image',
        'robots_txt_custom',
        'schema_organization',
    ];

    protected $casts = [
        'schema_organization' => 'array',
    ];

    /**
     * Get the singleton SEO settings instance
     */
    public static function getInstance(): self
    {
        return static::firstOrCreate([
            'id' => 1
        ], [
            'site_name' => 'City Life International Church',
            'site_description' => 'A vibrant spirit-filled multi-cultural church affiliated with the Assemblies of God, located in the heart of Kelham Island, Sheffield.',
            'default_keywords' => 'city life church, sheffield church, assemblies of god, kelham island church, christian church sheffield',
            'twitter_handle' => '@CityLifeChurch',
            'schema_organization' => [
                '@context' => 'https://schema.org',
                '@type' => 'Church',
                'name' => 'City Life International Church',
                'description' => 'A vibrant spirit-filled multi-cultural church affiliated with the Assemblies of God',
                'url' => config('app.url'),
                'telephone' => '+44 114 555 0123',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => '1 South Parade, Spaldesmoor',
                    'addressLocality' => 'Sheffield',
                    'postalCode' => 'S3 8ZZ',
                    'addressCountry' => 'UK'
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => 53.3941,
                    'longitude' => -1.4730
                ],
                'sameAs' => [
                    'https://www.facebook.com/CityLifeChurch',
                    'https://www.instagram.com/citylifechurch',
                    'https://www.youtube.com/citylifechurch'
                ]
            ]
        ]);
    }
}
