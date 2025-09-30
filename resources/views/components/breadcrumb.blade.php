@props(['breadcrumbs' => []])

@if(count($breadcrumbs) > 0)
@php
    $seoService = app(\App\Services\SEOService::class);
    $structuredData = $seoService->generateBreadcrumbStructuredData($breadcrumbs);
@endphp

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center space-x-2 text-sm text-gray-600">
        @foreach($breadcrumbs as $index => $breadcrumb)
            <li class="flex items-center">
                @if($index > 0)
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif

                @if($index === count($breadcrumbs) - 1)
                    <span class="font-medium text-gray-900" aria-current="page">{{ $breadcrumb['name'] }}</span>
                @else
                    <a href="{{ $breadcrumb['url'] }}" class="hover:text-blue-600 transition-colors duration-200">
                        {{ $breadcrumb['name'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

<!-- Structured Data for Breadcrumbs -->
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
