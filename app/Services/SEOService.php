<?php

namespace App\Services;

use App\Models\Event;
use App\Models\News;
use App\Models\TeachingSeries;
use App\Models\AboutPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SEOService
{
    /**
     * Generate SEO meta tags for a given model
     */
    public function generateMetaTags($model, ?string $type = null): array
    {
        $type = $type ?? $this->getModelType($model);

        return match($type) {
            'event' => $this->generateEventMetaTags($model),
            'news' => $this->generateNewsMetaTags($model),
            'teaching_series' => $this->generateTeachingSeriesMetaTags($model),
            'about' => $this->generateAboutMetaTags($model),
            default => $this->generateDefaultMetaTags()
        };
    }

    /**
     * Generate event-specific meta tags
     */
    protected function generateEventMetaTags(Event $event): array
    {
        $description = $this->truncateText($event->description, 160);
        $keywords = $this->generateEventKeywords($event);

        return [
            'title' => $event->title . ' - City Life Church Event',
            'description' => $description,
            'keywords' => implode(', ', $keywords),
            'canonical' => $this->safeRoute('events.show', ['slug' => $event->slug]) ?? '#',
            'og_title' => $event->title,
            'og_description' => $description,
            'og_type' => 'event',
            'og_url' => $this->safeRoute('events.show', ['slug' => $event->slug]) ?? '#',
            'og_image' => $event->featured_image_url,
            'og_site_name' => 'City Life International Church',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $event->title,
            'twitter_description' => $description,
            'twitter_image' => $event->featured_image_url,
            'structured_data' => $this->generateEventStructuredData($event),
        ];
    }

    /**
     * Generate news-specific meta tags
     */
    protected function generateNewsMetaTags(News $news): array
    {
        $description = $this->truncateText($news->excerpt, 160);
        $keywords = $this->generateNewsKeywords($news);

        return [
            'title' => $news->title . ' - City Life Church News',
            'description' => $description,
            'keywords' => implode(', ', $keywords),
            'canonical' => $this->safeRoute('news.show', ['slug' => $news->slug]) ?? '#',
            'og_title' => $news->title,
            'og_description' => $description,
            'og_type' => 'article',
            'og_url' => $this->safeRoute('news.show', ['slug' => $news->slug]) ?? '#',
            'og_image' => $news->featured_image ? asset('storage/' . $news->featured_image) : null,
            'og_site_name' => 'City Life International Church',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $news->title,
            'twitter_description' => $description,
            'twitter_image' => $news->featured_image ? asset('storage/' . $news->featured_image) : null,
            'article_author' => $news->author,
            'article_published_time' => $news->published_at?->toISOString(),
            'structured_data' => $this->generateNewsStructuredData($news),
        ];
    }

    /**
     * Generate teaching series meta tags
     */
    protected function generateTeachingSeriesMetaTags(TeachingSeries $series): array
    {
        // Prioritize sermon notes content for description if available
        $description = '';
        if ($series->sermon_notes_content) {
            $plainText = strip_tags($series->sermon_notes_content);
            $description = $this->truncateText($plainText, 160);
        } else {
            $description = $this->truncateText($series->description ?? $series->summary, 160);
        }

        $keywords = $this->generateTeachingSeriesKeywords($series);

        return [
            'title' => $series->title . ' - City Life Church Teaching',
            'description' => $description,
            'keywords' => implode(', ', $keywords),
            'canonical' => $this->safeRoute('teaching-series.show', ['slug' => $series->slug]) ?? '#',
            'og_title' => $series->title,
            'og_description' => $description,
            'og_type' => 'video.other',
            'og_url' => $this->safeRoute('teaching-series.show', ['slug' => $series->slug]) ?? '#',
            'og_image' => $series->image ? asset('storage/' . $series->image) : null,
            'og_site_name' => 'City Life International Church',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $series->title,
            'twitter_description' => $description,
            'twitter_image' => $series->image ? asset('storage/' . $series->image) : null,
            'structured_data' => $this->generateTeachingSeriesStructuredData($series),
        ];
    }

    /**
     * Generate about page meta tags
     */
    protected function generateAboutMetaTags(AboutPage $about): array
    {
        $description = $this->truncateText($about->meta_description ?? $about->description, 160);

        return [
            'title' => $about->meta_title ?? 'About City Life International Church',
            'description' => $description,
            'keywords' => $about->meta_keywords ?? 'city life church, about us, sheffield church, assemblies of god',
            'canonical' => $this->safeRoute('about') ?? '#',
            'og_title' => $about->meta_title ?? 'About City Life International Church',
            'og_description' => $description,
            'og_type' => 'website',
            'og_url' => $this->safeRoute('about') ?? '#',
            'og_image' => $about->hero_image ? asset('storage/' . $about->hero_image) : null,
            'og_site_name' => 'City Life International Church',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $about->meta_title ?? 'About City Life International Church',
            'twitter_description' => $description,
            'twitter_image' => $about->hero_image ? asset('storage/' . $about->hero_image) : null,
        ];
    }

    /**
     * Generate default meta tags
     */
    protected function generateDefaultMetaTags(): array
    {
        return [
            'title' => 'City Life International Church - Sheffield',
            'description' => 'A vibrant spirit-filled multi-cultural church affiliated with the Assemblies of God, located in the heart of Kelham Island, Sheffield.',
            'keywords' => 'city life church, sheffield church, assemblies of god, kelham island church, christian church sheffield',
            'canonical' => $this->safeRoute('home') ?? '/',
            'og_title' => 'City Life International Church',
            'og_description' => 'A vibrant spirit-filled multi-cultural church affiliated with the Assemblies of God, located in the heart of Kelham Island, Sheffield.',
            'og_type' => 'website',
            'og_url' => $this->safeRoute('home') ?? '/',
            'og_site_name' => 'City Life International Church',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => 'City Life International Church',
            'twitter_description' => 'A vibrant spirit-filled multi-cultural church affiliated with the Assemblies of God, located in the heart of Kelham Island, Sheffield.',
        ];
    }

    /**
     * Generate XML sitemap
     */
    public function generateSitemap(): string
    {
        $cacheKey = 'sitemap_xml';

        return Cache::remember($cacheKey, now()->addHours(6), function () {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            // Homepage
            $xml .= $this->addSitemapUrl($this->safeRoute('home') ?? '/', now(), 'daily', '1.0');

            // About page
            $xml .= $this->addSitemapUrl($this->safeRoute('about') ?? '/about', now()->subDays(7), 'weekly', '0.9');

            // Events
            $events = Event::published()->get();
            foreach ($events as $event) {
                $eventUrl = $this->safeRoute('events.show', ['slug' => $event->slug]);
                if ($eventUrl) {
                    $xml .= $this->addSitemapUrl(
                        $eventUrl,
                        $event->updated_at,
                        'weekly',
                        '0.8'
                    );
                }
            }

            // News
            try {
                $news = News::published()->get();
                foreach ($news as $article) {
                    $newsUrl = $this->safeRoute('news.show', ['slug' => $article->slug]);
                    if ($newsUrl) {
                        $xml .= $this->addSitemapUrl(
                            $newsUrl,
                            $article->updated_at,
                            'monthly',
                            '0.7'
                        );
                    }
                }
            } catch (\Exception $e) {
                // News model may not exist or table may not exist
            }

            // Teaching Series
            try {
                $teachingSeries = TeachingSeries::where('is_published', true)->get();
                foreach ($teachingSeries as $series) {
                    $seriesUrl = $this->safeRoute('teaching-series.show', ['slug' => $series->slug]);
                    if ($seriesUrl) {
                        $xml .= $this->addSitemapUrl(
                            $seriesUrl,
                            $series->updated_at,
                            'monthly',
                            '0.7'
                        );
                    }
                }
            } catch (\Exception $e) {
                // TeachingSeries model may not exist or table may not exist
            }

            // Static pages
            $staticPages = [
                ['url' => $this->safeRoute('contact'), 'priority' => '0.8'],
                ['url' => $this->safeRoute('events.index'), 'priority' => '0.9'],
                ['url' => $this->safeRoute('teaching-series.index'), 'priority' => '0.8'],
                ['url' => $this->safeRoute('giving.index'), 'priority' => '0.6'],
                ['url' => $this->safeRoute('volunteer.index'), 'priority' => '0.7'],
            ];

            foreach ($staticPages as $page) {
                if ($page['url']) {
                    $xml .= $this->addSitemapUrl(
                        $page['url'],
                        now()->subDays(3),
                        'weekly',
                        $page['priority']
                    );
                }
            }

            $xml .= '</urlset>';

            return $xml;
        });
    }

    /**
     * Generate robots.txt content
     */
    public function generateRobotsTxt(): string
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /filament/\n";
        $content .= "Disallow: /storage/\n";
        $content .= "Disallow: /vendor/\n";
        $content .= "\n";
        $content .= "Sitemap: " . ($this->safeRoute('sitemap') ?? url('/sitemap.xml')) . "\n";

        return $content;
    }

    /**
     * Generate structured data for events
     */
    protected function generateEventStructuredData(Event $event): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->title,
            'description' => $event->description,
            'startDate' => $event->start_date->toISOString(),
            'endDate' => $event->end_date?->toISOString(),
            'location' => [
                '@type' => 'Place',
                'name' => $event->location,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => '1 South Parade, Spaldesmoor',
                    'addressLocality' => 'Sheffield',
                    'postalCode' => 'S3 8ZZ',
                    'addressCountry' => 'UK'
                ]
            ],
            'organizer' => [
                '@type' => 'Organization',
                'name' => 'City Life International Church',
                'url' => $this->safeRoute('home') ?? '/'
            ],
            'image' => $event->featured_image_url,
            'url' => $this->safeRoute('events.show', ['slug' => $event->slug]) ?? '#',
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        ];
    }

    /**
     * Generate structured data for news articles
     */
    protected function generateNewsStructuredData(News $news): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $news->title,
            'description' => $news->excerpt,
            'author' => [
                '@type' => 'Person',
                'name' => $news->author ?? 'City Life Church'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'City Life International Church',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets/images/logo.png')
                ]
            ],
            'datePublished' => $news->published_at?->toISOString(),
            'dateModified' => $news->updated_at->toISOString(),
            'image' => $news->featured_image ? asset('storage/' . $news->featured_image) : null,
            'url' => $this->safeRoute('news.show', ['slug' => $news->slug]) ?? '#',
        ];
    }

    /**
     * Generate structured data for teaching series
     */
    protected function generateTeachingSeriesStructuredData(TeachingSeries $series): array
    {
        // Use sermon notes content for description if available
        $description = $series->description ?? $series->summary;
        if ($series->sermon_notes_content) {
            $plainText = strip_tags($series->sermon_notes_content);
            $description = $this->truncateText($plainText, 500);
        }

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $series->title,
            'description' => $description,
            'thumbnailUrl' => $series->image ? asset('storage/' . $series->image) : null,
            'uploadDate' => $series->series_date?->toISOString(),
            'duration' => $series->duration_minutes ? 'PT' . $series->duration_minutes . 'M' : null,
            'contentUrl' => $series->video_url,
            'embedUrl' => $series->video_url,
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'City Life International Church',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets/images/logo.png')
                ]
            ],
        ];

        // Add transcript if sermon notes content is available
        if ($series->sermon_notes_content) {
            $structuredData['transcript'] = [
                '@type' => 'MediaObject',
                'text' => strip_tags($series->sermon_notes_content)
            ];
        }

        return $structuredData;
    }

    /**
     * Generate keywords for events
     */
    protected function generateEventKeywords(Event $event): array
    {
        $keywords = ['city life church', 'sheffield church', 'church events'];

        // Add event-specific keywords
        if ($event->event_anchor) {
            $keywords[] = $event->event_anchor;
        }

        if ($event->guest_speaker) {
            $keywords[] = $event->guest_speaker;
        }

        // Add location-based keywords
        if (str_contains(strtolower($event->location), 'sanctuary')) {
            $keywords[] = 'church sanctuary';
        }

        // Add date-based keywords
        $keywords[] = $event->start_date->format('F Y');

        return array_unique($keywords);
    }

    /**
     * Generate keywords for news
     */
    protected function generateNewsKeywords(News $news): array
    {
        $keywords = ['city life church', 'church news', 'sheffield church'];

        if ($news->author) {
            $keywords[] = $news->author;
        }

        // Extract keywords from title
        $titleWords = str_word_count(strtolower($news->title), 1);
        $keywords = array_merge($keywords, array_slice($titleWords, 0, 3));

        return array_unique($keywords);
    }

    /**
     * Generate keywords for teaching series
     */
    protected function generateTeachingSeriesKeywords(TeachingSeries $series): array
    {
        $keywords = ['city life church', 'church teaching', 'sermon', 'bible study'];

        if ($series->pastor) {
            $keywords[] = $series->pastor;
        }

        if ($series->category) {
            $keywords[] = $series->category;
        }

        // Add tags if available
        if ($series->tags) {
            $tags = is_array($series->tags) ? $series->tags : json_decode($series->tags, true);
            if ($tags) {
                $keywords = array_merge($keywords, $tags);
            }
        }

        return array_unique($keywords);
    }

    /**
     * Add URL to sitemap XML
     */
    protected function addSitemapUrl(string $url, $lastmod, string $changefreq, string $priority): string
    {
        $xml = "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
        $xml .= "    <lastmod>" . $lastmod->toW3CString() . "</lastmod>\n";
        $xml .= "    <changefreq>{$changefreq}</changefreq>\n";
        $xml .= "    <priority>{$priority}</priority>\n";
        $xml .= "  </url>\n";

        return $xml;
    }

    /**
     * Get model type from object
     */
    protected function getModelType($model): string
    {
        if (!$model) {
            return 'default';
        }

        $class = get_class($model);
        return match($class) {
            Event::class => 'event',
            News::class => 'news',
            TeachingSeries::class => 'teaching_series',
            AboutPage::class => 'about',
            default => 'default'
        };
    }

    /**
     * Truncate text to specified length
     */
    protected function truncateText(string $text, int $length): string
    {
        return Str::limit(strip_tags($text), $length);
    }

    /**
     * Generate breadcrumb structured data
     */
    public function generateBreadcrumbStructuredData(array $breadcrumbs): array
    {
        $itemListElement = [];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement
        ];
    }

    /**
     * Clear SEO cache
     */
    public function clearCache(): void
    {
        Cache::forget('sitemap_xml');
    }

    /**
     * Safe route generation - returns null if route doesn't exist
     */
    protected function safeRoute(string $routeName, array $parameters = []): ?string
    {
        try {
            return route($routeName, $parameters);
        } catch (\Exception $e) {
            return null;
        }
    }
}
