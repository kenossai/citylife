<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SEOService;
use Illuminate\Support\Facades\Storage;

class GenerateSEOSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:generate-sitemap {--clear-cache : Clear existing sitemap cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and cache SEO sitemap for the website';

    protected SEOService $seoService;

    public function __construct(SEOService $seoService)
    {
        parent::__construct();
        $this->seoService = $seoService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Generating SEO sitemap...');

        if ($this->option('clear-cache')) {
            $this->info('🗑️ Clearing sitemap cache...');
            $this->seoService->clearCache();
        }

        try {
            // Generate sitemap
            $sitemap = $this->seoService->generateSitemap();

            // Save to public directory
            Storage::disk('public')->put('sitemap.xml', $sitemap);

            // Generate robots.txt
            $robots = $this->seoService->generateRobotsTxt();
            Storage::disk('public')->put('robots.txt', $robots);

            $this->info('✅ SEO sitemap generated successfully!');
            $this->line('   📄 Sitemap saved to: storage/app/public/sitemap.xml');
            $this->line('   🤖 Robots.txt saved to: storage/app/public/robots.txt');

            // Show summary
            $this->showSummary();

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Failed to generate sitemap: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    /**
     * Show sitemap generation summary
     */
    protected function showSummary(): void
    {
        $this->info('📊 Sitemap Summary:');

        // Count URLs by type
        $events = \App\Models\Event::published()->count();
        $news = \App\Models\News::published()->count();
        $teachings = \App\Models\TeachingSeries::where('is_published', true)->count();

        $this->table(['Content Type', 'Count'], [
            ['Events', $events],
            ['News Articles', $news],
            ['Teaching Series', $teachings],
            ['Static Pages', '6+'],
        ]);

        $this->line('');
        $this->info('🌐 Access your sitemap at: ' . route('sitemap'));
        $this->info('🤖 Access robots.txt at: ' . route('robots'));
    }
}
