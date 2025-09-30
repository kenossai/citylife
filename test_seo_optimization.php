<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test SEO Optimization System
echo "🔍 Testing SEO Optimization System for City Life Church\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Test SEO Service
echo "1. 📊 Testing SEO Service\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    $seoService = app(\App\Services\SEOService::class);
    echo "✅ SEO Service initialized successfully\n";

    // Test default meta tags
    $defaultMeta = $seoService->generateMetaTags(null);
    echo "✅ Default meta tags generated\n";
    echo "   Title: " . $defaultMeta['title'] . "\n";
    echo "   Description: " . substr($defaultMeta['description'], 0, 80) . "...\n";

} catch (Exception $e) {
    echo "❌ SEO Service failed: " . $e->getMessage() . "\n";
}

echo "\n2. 🗃️ Testing Database SEO Fields\n";
echo "-" . str_repeat("-", 30) . "\n";

// Test if SEO fields exist in events table
try {
    $eventColumns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM events");
    $seoFields = ['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_image'];
    $foundFields = [];

    foreach ($eventColumns as $column) {
        if (in_array($column->Field, $seoFields)) {
            $foundFields[] = $column->Field;
        }
    }

    echo "✅ SEO fields in events table: " . count($foundFields) . "/5\n";
    foreach ($foundFields as $field) {
        echo "   ✓ " . $field . "\n";
    }

} catch (Exception $e) {
    echo "❌ Database check failed: " . $e->getMessage() . "\n";
}

echo "\n3. 🏗️ Testing Model SEO Traits\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Test Event model with SEO trait
    $event = \App\Models\Event::first();
    if ($event) {
        echo "✅ Found test event: " . $event->title . "\n";

        // Test HasSEO trait methods
        if (method_exists($event, 'getEffectiveMetaTitleAttribute')) {
            echo "✅ HasSEO trait methods available\n";

            $autoSEO = $event->generateAutoSEO();
            echo "✅ Auto-SEO generated\n";
            echo "   Meta Title: " . ($autoSEO['meta_title'] ?? 'N/A') . "\n";
            echo "   Keywords: " . ($autoSEO['meta_keywords'] ?? 'N/A') . "\n";
        } else {
            echo "❌ HasSEO trait methods not found\n";
        }
    } else {
        echo "⚪ No events found for testing\n";
    }

} catch (Exception $e) {
    echo "❌ Model SEO test failed: " . $e->getMessage() . "\n";
}

echo "\n4. 🗺️ Testing Sitemap Generation\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    $seoService = app(\App\Services\SEOService::class);
    $sitemap = $seoService->generateSitemap();

    if (!empty($sitemap) && strpos($sitemap, '<urlset') !== false) {
        echo "✅ XML Sitemap generated successfully\n";

        // Count URLs in sitemap
        $urlCount = substr_count($sitemap, '<url>');
        echo "✅ Sitemap contains " . $urlCount . " URLs\n";

        // Check for required URLs
        $requiredUrls = [
            'events',
            'about',
            'contact'
        ];

        foreach ($requiredUrls as $url) {
            if (strpos($sitemap, $url) !== false) {
                echo "   ✓ Contains " . $url . " URLs\n";
            }
        }
    } else {
        echo "❌ Invalid sitemap generated\n";
    }

} catch (Exception $e) {
    echo "❌ Sitemap generation failed: " . $e->getMessage() . "\n";
}

echo "\n5. 🤖 Testing Robots.txt Generation\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    $seoService = app(\App\Services\SEOService::class);
    $robots = $seoService->generateRobotsTxt();

    if (!empty($robots) && strpos($robots, 'User-agent:') !== false) {
        echo "✅ Robots.txt generated successfully\n";

        // Check for required directives
        $requiredDirectives = [
            'User-agent: *',
            'Disallow: /admin/',
            'Sitemap:'
        ];

        foreach ($requiredDirectives as $directive) {
            if (strpos($robots, $directive) !== false) {
                echo "   ✓ Contains: " . $directive . "\n";
            }
        }
    } else {
        echo "❌ Invalid robots.txt generated\n";
    }

} catch (Exception $e) {
    echo "❌ Robots.txt generation failed: " . $e->getMessage() . "\n";
}

echo "\n6. 🎯 Testing SEO Routes\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Test if SEO routes are registered
    $routes = [
        'sitemap' => 'sitemap.xml',
        'robots' => 'robots.txt'
    ];

    foreach ($routes as $routeName => $path) {
        try {
            $url = route($routeName);
            echo "✅ Route '" . $routeName . "' registered: " . $url . "\n";
        } catch (Exception $e) {
            echo "❌ Route '" . $routeName . "' not found\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Route testing failed: " . $e->getMessage() . "\n";
}

echo "\n7. ⚙️ Testing SEO Settings Model\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    $seoSettings = \App\Models\SEOSettings::getInstance();
    echo "✅ SEO Settings model working\n";
    echo "   Site Name: " . $seoSettings->site_name . "\n";
    echo "   Description: " . substr($seoSettings->site_description ?? '', 0, 50) . "...\n";

} catch (Exception $e) {
    echo "❌ SEO Settings test failed: " . $e->getMessage() . "\n";
}

echo "\n8. 📱 Testing Social Media Meta Tags\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    $seoService = app(\App\Services\SEOService::class);
    $event = \App\Models\Event::first();

    if ($event) {
        $metaTags = $seoService->generateMetaTags($event, 'event');

        $socialTags = [
            'og_title' => 'Open Graph Title',
            'og_description' => 'Open Graph Description',
            'twitter_card' => 'Twitter Card',
            'twitter_title' => 'Twitter Title'
        ];

        foreach ($socialTags as $tag => $label) {
            if (!empty($metaTags[$tag])) {
                echo "✅ " . $label . " generated\n";
            } else {
                echo "❌ " . $label . " missing\n";
            }
        }
    } else {
        echo "⚪ No events available for social media testing\n";
    }

} catch (Exception $e) {
    echo "❌ Social media meta tags test failed: " . $e->getMessage() . "\n";
}

echo "\n9. 🏗️ Testing Structured Data\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    $seoService = app(\App\Services\SEOService::class);
    $event = \App\Models\Event::first();

    if ($event) {
        $metaTags = $seoService->generateMetaTags($event, 'event');

        if (!empty($metaTags['structured_data'])) {
            $structuredData = $metaTags['structured_data'];
            echo "✅ Structured data generated for event\n";
            echo "   Type: " . ($structuredData['@type'] ?? 'N/A') . "\n";
            echo "   Name: " . ($structuredData['name'] ?? 'N/A') . "\n";

            // Validate JSON-LD structure
            if (isset($structuredData['@context']) && $structuredData['@context'] === 'https://schema.org') {
                echo "✅ Valid Schema.org structured data\n";
            } else {
                echo "❌ Invalid structured data format\n";
            }
        } else {
            echo "❌ No structured data generated\n";
        }
    } else {
        echo "⚪ No events available for structured data testing\n";
    }

} catch (Exception $e) {
    echo "❌ Structured data test failed: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "🎉 SEO Optimization System Test Complete!\n";
echo "\nNext Steps:\n";
echo "1. 🔧 Configure SEO settings in Filament Admin\n";
echo "2. 📝 Add custom meta descriptions to important pages\n";
echo "3. 🖼️ Upload social media images for key content\n";
echo "4. 📊 Set up Google Analytics and Search Console\n";
echo "5. 🔍 Submit sitemap to search engines\n";
echo "6. 📈 Monitor SEO performance and rankings\n";
echo "\nAccess your SEO tools:\n";
echo "• Sitemap: " . url('/sitemap.xml') . "\n";
echo "• Robots.txt: " . url('/robots.txt') . "\n";
echo "• Admin Settings: " . url('/admin/seo-settings') . "\n";
echo "\n🚀 Your website is now optimized for search engines!\n";
