<?php

namespace App\Http\Controllers;

use App\Services\SEOService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SEOController extends Controller
{
    protected SEOService $seoService;

    public function __construct(SEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Generate and return XML sitemap
     */
    public function sitemap(): Response
    {
        $sitemapContent = $this->seoService->generateSitemap();

        return response($sitemapContent, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Generate and return robots.txt
     */
    public function robots(): Response
    {
        $robotsContent = $this->seoService->generateRobotsTxt();

        return response($robotsContent, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
