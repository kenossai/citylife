@extends('layouts.app')

@section('title', $category->name . ' - ' . ($settings['cafe_name'] ?? 'CityLife Cafe'))

@section('meta_description', $category->description ?? 'Browse our ' . $category->name . ' menu')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
        <div class="container mx-auto px-4">
            <nav class="text-sm mb-4 opacity-75">
                <a href="{{ route('cafe.menu') }}" class="hover:text-white">Menu</a>
                <span class="mx-2">›</span>
                <span>{{ $category->name }}</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-lg opacity-90">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if($category->products->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($category->products as $product)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-800">{{ $product->name }}</h3>
                                <span class="text-xl font-bold text-blue-600">£{{ number_format($product->price, 2) }}</span>
                            </div>

                            @if($product->description)
                                <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                            @endif

                            <!-- Product Details -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($product->size)
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">{{ ucfirst($product->size) }}</span>
                                @endif
                                @if($product->temperature)
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">{{ ucfirst(str_replace('_', ' ', $product->temperature)) }}</span>
                                @endif
                                @if($product->preparation_time)
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">{{ $product->preparation_time }} min</span>
                                @endif
                            </div>

                            <!-- Dietary Info -->
                            @if($product->dietary_info && count($product->dietary_info) > 0)
                                <div class="flex flex-wrap gap-1 mb-4">
                                    @foreach($product->dietary_info as $diet)
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">{{ ucfirst(str_replace('_', ' ', $diet)) }}</span>
                                    @endforeach
                                </div>
                            @endif

                            @if($product->ingredients)
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">Ingredients:</h4>
                                    <p class="text-gray-600 text-sm">{{ $product->ingredients }}</p>
                                </div>
                            @endif

                            <a href="{{ route('cafe.product', [$category->slug, $product->slug]) }}"
                               class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(($settings['allow_online_ordering'] ?? 'false') === 'true')
                <div class="text-center mt-12">
                    <a href="{{ route('cafe.order.create') }}"
                       class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition duration-300 inline-block">
                        Order Online
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="bg-white rounded-lg shadow-lg p-12 max-w-lg mx-auto">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">No Items Available</h2>
                    <p class="text-gray-600 mb-6">There are currently no items in this category.</p>
                    <a href="{{ route('cafe.menu') }}"
                       class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Back to Menu
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
