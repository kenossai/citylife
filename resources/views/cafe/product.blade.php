@extends('layouts.app')

@section('title', $product->name . ' - ' . ($settings['cafe_name'] ?? 'CityLife Cafe'))

@section('meta_description', $product->description ?? 'View details for ' . $product->name)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
        <div class="container mx-auto px-4">
            <nav class="text-sm mb-4 opacity-75">
                <a href="{{ route('cafe.menu') }}" class="hover:text-white">Menu</a>
                <span class="mx-2">›</span>
                <a href="{{ route('cafe.category', $category->slug) }}" class="hover:text-white">{{ $category->name }}</a>
                <span class="mx-2">›</span>
                <span>{{ $product->name }}</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-bold">{{ $product->name }}</h1>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid lg:grid-cols-2 gap-8 mb-12">
            <!-- Product Image -->
            <div>
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-96 object-cover rounded-lg shadow-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg shadow-lg flex items-center justify-center">
                        <span class="text-gray-400 text-lg">No Image Available</span>
                    </div>
                @endif

                <!-- Gallery Images -->
                @if($product->gallery && count($product->gallery) > 0)
                    <div class="grid grid-cols-4 gap-2 mt-4">
                        @foreach($product->gallery as $galleryImage)
                            <img src="{{ Storage::url($galleryImage) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75 transition duration-300"
                                 onclick="changeMainImage('{{ Storage::url($galleryImage) }}')">
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h2>
                    <span class="text-3xl font-bold text-blue-600">£{{ number_format($product->price, 2) }}</span>
                </div>

                @if($product->description)
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->description }}</p>
                @endif

                <!-- Product Information -->
                <div class="space-y-4 mb-6">
                    @if($product->size || $product->temperature || $product->preparation_time)
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Product Details</h3>
                            <div class="flex flex-wrap gap-2">
                                @if($product->size)
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Size: {{ ucfirst($product->size) }}</span>
                                @endif
                                @if($product->temperature)
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">{{ ucfirst(str_replace('_', ' ', $product->temperature)) }}</span>
                                @endif
                                @if($product->preparation_time)
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Prep time: {{ $product->preparation_time }} min</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($product->dietary_info && count($product->dietary_info) > 0)
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Dietary Information</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->dietary_info as $diet)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">{{ ucfirst(str_replace('_', ' ', $diet)) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($product->ingredients)
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Ingredients</h3>
                            <p class="text-gray-600 text-sm">{{ $product->ingredients }}</p>
                        </div>
                    @endif

                    @if($product->nutritional_info && count($product->nutritional_info) > 0)
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Nutritional Information</h3>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                @foreach($product->nutritional_info as $nutrition)
                                    <div class="flex justify-between bg-gray-50 px-3 py-1 rounded">
                                        <span class="text-gray-600">{{ $nutrition['nutrient'] }}:</span>
                                        <span class="font-medium">{{ $nutrition['value'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @if(($settings['allow_online_ordering'] ?? 'false') === 'true')
                    <a href="{{ route('cafe.order.create') }}"
                       class="block w-full text-center bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition duration-300 mb-4">
                        Add to Order
                    </a>
                @endif

                <a href="{{ route('cafe.category', $category->slug) }}"
                   class="block w-full text-center bg-gray-600 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition duration-300">
                    Back to {{ $category->name }}
                </a>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">More from {{ $category->name }}</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="border rounded-lg p-4 hover:shadow-md transition duration-300">
                            @if($relatedProduct->image)
                                <img src="{{ Storage::url($relatedProduct->image) }}"
                                     alt="{{ $relatedProduct->name }}"
                                     class="w-full h-32 object-cover rounded-lg mb-3">
                            @endif

                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-800">{{ $relatedProduct->name }}</h4>
                                <span class="text-lg font-bold text-blue-600">£{{ number_format($relatedProduct->price, 2) }}</span>
                            </div>

                            @if($relatedProduct->description)
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($relatedProduct->description, 60) }}</p>
                            @endif

                            <a href="{{ route('cafe.product', [$category->slug, $relatedProduct->slug]) }}"
                               class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300 text-sm">
                                View Details
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function changeMainImage(imageSrc) {
    const mainImage = document.querySelector('img[alt="{{ $product->name }}"]');
    if (mainImage) {
        mainImage.src = imageSrc;
    }
}
</script>
@endsection
