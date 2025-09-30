<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SEOService;

class SEOMiddleware
{
    protected SEOService $seoService;

    public function __construct(SEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only process HTML responses
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        // Get the current model from the route
        $model = $this->extractModelFromRoute($request);

        if ($model) {
            // Generate meta tags for the model
            $metaTags = $this->seoService->generateMetaTags($model);

            // Inject meta tags into the response
            $content = $response->getContent();
            $content = $this->injectMetaTags($content, $metaTags);
            $response->setContent($content);
        }

        return $response;
    }

    /**
     * Check if the response is HTML
     */
    protected function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        return strpos($contentType, 'text/html') !== false ||
               (empty($contentType) && is_string($response->getContent()));
    }

    /**
     * Extract model from the current route
     */
    protected function extractModelFromRoute(Request $request)
    {
        $route = $request->route();

        if (!$route) {
            return null;
        }

        // Look for models in route parameters
        foreach ($route->parameters() as $parameter) {
            if (is_object($parameter) && method_exists($parameter, 'getTable')) {
                return $parameter;
            }
        }

        return null;
    }

    /**
     * Inject meta tags into HTML content
     */
    protected function injectMetaTags(string $content, array $metaTags): string
    {
        // Find the </head> tag and inject meta tags before it
        $headClosePos = strpos($content, '</head>');

        if ($headClosePos === false) {
            return $content;
        }

        $metaTagsHtml = $this->buildMetaTagsHtml($metaTags);

        return substr_replace($content, $metaTagsHtml . "\n", $headClosePos, 0);
    }

    /**
     * Build HTML for meta tags
     */
    protected function buildMetaTagsHtml(array $metaTags): string
    {
        $html = "\n    <!-- Auto-generated SEO Meta Tags -->\n";

        // Title
        if (!empty($metaTags['title'])) {
            $html .= "    <title>" . htmlspecialchars($metaTags['title']) . "</title>\n";
        }

        // Meta description
        if (!empty($metaTags['description'])) {
            $html .= "    <meta name=\"description\" content=\"" . htmlspecialchars($metaTags['description']) . "\">\n";
        }

        // Meta keywords
        if (!empty($metaTags['keywords'])) {
            $html .= "    <meta name=\"keywords\" content=\"" . htmlspecialchars($metaTags['keywords']) . "\">\n";
        }

        // Canonical URL
        if (!empty($metaTags['canonical'])) {
            $html .= "    <link rel=\"canonical\" href=\"" . htmlspecialchars($metaTags['canonical']) . "\">\n";
        }

        // Open Graph tags
        $ogTags = ['og_title', 'og_description', 'og_type', 'og_url', 'og_image', 'og_site_name'];
        foreach ($ogTags as $tag) {
            if (!empty($metaTags[$tag])) {
                $property = str_replace('_', ':', $tag);
                $html .= "    <meta property=\"{$property}\" content=\"" . htmlspecialchars($metaTags[$tag]) . "\">\n";
            }
        }

        // Twitter Card tags
        $twitterTags = ['twitter_card', 'twitter_title', 'twitter_description', 'twitter_image'];
        foreach ($twitterTags as $tag) {
            if (!empty($metaTags[$tag])) {
                $name = str_replace('_', ':', $tag);
                $html .= "    <meta name=\"{$name}\" content=\"" . htmlspecialchars($metaTags[$tag]) . "\">\n";
            }
        }

        // Article tags
        if (!empty($metaTags['article_author'])) {
            $html .= "    <meta property=\"article:author\" content=\"" . htmlspecialchars($metaTags['article_author']) . "\">\n";
        }

        if (!empty($metaTags['article_published_time'])) {
            $html .= "    <meta property=\"article:published_time\" content=\"" . htmlspecialchars($metaTags['article_published_time']) . "\">\n";
        }

        // Structured Data
        if (!empty($metaTags['structured_data'])) {
            $html .= "    <script type=\"application/ld+json\">\n";
            $html .= "    " . json_encode($metaTags['structured_data'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
            $html .= "    </script>\n";
        }

        $html .= "    <!-- End Auto-generated SEO Meta Tags -->\n";

        return $html;
    }
}
