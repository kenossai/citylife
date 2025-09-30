@props(['model' => null, 'type' => null])

@php
    $seoService = app(\App\Services\SEOService::class);
    $metaTags = $model ? $seoService->generateMetaTags($model, $type) : $seoService->generateMetaTags(null);
@endphp

<title>{{ $metaTags['title'] }}</title>
<meta name="description" content="{{ $metaTags['description'] }}">
<meta name="keywords" content="{{ $metaTags['keywords'] }}">
<link rel="canonical" href="{{ $metaTags['canonical'] }}">

<!-- Open Graph tags -->
<meta property="og:title" content="{{ $metaTags['og_title'] }}">
<meta property="og:description" content="{{ $metaTags['og_description'] }}">
<meta property="og:type" content="{{ $metaTags['og_type'] }}">
<meta property="og:url" content="{{ $metaTags['og_url'] }}">
@if($metaTags['og_image'])
<meta property="og:image" content="{{ $metaTags['og_image'] }}">
@endif
<meta property="og:site_name" content="{{ $metaTags['og_site_name'] }}">

<!-- Twitter Card tags -->
<meta name="twitter:card" content="{{ $metaTags['twitter_card'] }}">
<meta name="twitter:title" content="{{ $metaTags['twitter_title'] }}">
<meta name="twitter:description" content="{{ $metaTags['twitter_description'] }}">
@if($metaTags['twitter_image'])
<meta name="twitter:image" content="{{ $metaTags['twitter_image'] }}">
@endif

<!-- Article specific tags for news -->
@if(isset($metaTags['article_author']))
<meta property="article:author" content="{{ $metaTags['article_author'] }}">
@endif
@if(isset($metaTags['article_published_time']))
<meta property="article:published_time" content="{{ $metaTags['article_published_time'] }}">
@endif

<!-- Structured Data -->
@if(isset($metaTags['structured_data']))
<script type="application/ld+json">
{!! json_encode($metaTags['structured_data'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
