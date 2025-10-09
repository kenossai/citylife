<x-app-layout>

@section('title', ($settings['cafe_name'] ?? 'CityLife Cafe') . ' - Menu')

@section('meta_description', $settings['cafe_description'] ?? 'Enjoy great food and fellowship at our church cafe')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                {{ $settings['cafe_name'] ?? 'CityLife Cafe' }}
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">
                {{ $settings['cafe_description'] ?? 'A warm and welcoming place to enjoy great food and fellowship' }}
            </p>

            @if(($settings['allow_online_ordering'] ?? 'false') === 'true')
                <a href="{{ route('cafe.order.create') }}"
                   class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 inline-block">
                    Order Online
                </a>
            @endif
        </div>
    </div>

    <!-- Opening Hours -->
    <div class="bg-white py-8 border-b">
        <div class="container mx-auto px-4">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Opening Hours</h2>
                <div class="grid md:grid-cols-4 gap-4 text-sm">
                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                        <div class="flex justify-between items-center p-2 {{ $day === strtolower(now()->format('l')) ? 'bg-blue-50 rounded font-semibold' : '' }}">
                            <span class="capitalize">{{ $day }}:</span>
                            <span>{{ $settings["opening_hours_{$day}"] ?? 'Closed' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Categories -->
    <div class="container mx-auto px-4 py-12">
        @if($categories->count() > 0)
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Our Menu</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    From freshly brewed coffee to delicious meals, we have something for everyone.
                </p>
            </div>

            <div class="space-y-16">
                @foreach($categories as $category)
                    @if($category->products->count() > 0)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <!-- Category Header -->
                            <div class="bg-gradient-to-r from-gray-800 to-gray-700 text-white p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold">{{ $category->name }}</h3>
                                        @if($category->description)
                                            <p class="text-gray-300 mt-2">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('cafe.category', $category->slug) }}"
                                       class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition duration-300">
                                        View All
                                    </a>
                                </div>
                            </div>

                            <!-- Products Grid -->
                            <div class="p-6">
                                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($category->products->take(6) as $product)
                                        <div class="border rounded-lg p-4 hover:shadow-md transition duration-300">
                                            @if($product->image)
                                                <img src="{{ Storage::url($product->image) }}"
                                                     alt="{{ $product->name }}"
                                                     class="w-full h-32 object-cover rounded-lg mb-3">
                                            @endif

                                            <div class="flex justify-between items-start mb-2">
                                                <h4 class="font-semibold text-lg text-gray-800">{{ $product->name }}</h4>
                                                <span class="text-lg font-bold text-blue-600">£{{ number_format($product->price, 2) }}</span>
                                            </div>

                                            @if($product->description)
                                                <p class="text-gray-600 text-sm mb-3">{{ $product->description }}</p>
                                            @endif

                                            <!-- Product Details -->
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                @if($product->size)
                                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{{ ucfirst($product->size) }}</span>
                                                @endif
                                                @if($product->temperature)
                                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{{ ucfirst(str_replace('_', ' ', $product->temperature)) }}</span>
                                                @endif
                                                @if($product->preparation_time)
                                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{{ $product->preparation_time }} min</span>
                                                @endif
                                            </div>

                                            <!-- Dietary Info -->
                                            @if($product->dietary_info && count($product->dietary_info) > 0)
                                                <div class="flex flex-wrap gap-1 mb-3">
                                                    @foreach($product->dietary_info as $diet)
                                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">{{ ucfirst(str_replace('_', ' ', $diet)) }}</span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <a href="{{ route('cafe.product', [$category->slug, $product->slug]) }}"
                                               class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                                                View Details
                                            </a>
                                        </div>
                                    @endforeach
                                </div>

                                @if($category->products->count() > 6)
                                    <div class="text-center mt-6">
                                        <a href="{{ route('cafe.category', $category->slug) }}"
                                           class="inline-block bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                                            View All {{ $category->name }} ({{ $category->products->count() }} items)
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="bg-white rounded-lg shadow-lg p-12 max-w-lg mx-auto">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Menu Coming Soon</h2>
                    <p class="text-gray-600">Our delicious menu is being prepared. Please check back soon!</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Contact Information -->
    <div class="bg-white border-t py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Visit Us</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @if(isset($settings['cafe_phone']))
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Phone</h3>
                        <p class="text-gray-600">{{ $settings['cafe_phone'] }}</p>
                    </div>
                @endif

                @if(isset($settings['cafe_email']))
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Email</h3>
                        <p class="text-gray-600">{{ $settings['cafe_email'] }}</p>
                    </div>
                @endif

                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Payment Methods</h3>
                    <div class="flex justify-center gap-2">
                        @if(($settings['accept_cash'] ?? 'false') === 'true')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Cash</span>
                        @endif
                        @if(($settings['accept_card'] ?? 'false') === 'true')
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Card</span>
                            @if(isset($settings['minimum_card_amount']))
                                <span class="text-gray-500 text-sm">
                                    (Min £{{ number_format($settings['minimum_card_amount'], 2) }})
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
