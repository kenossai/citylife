<x-app-layout>

@section('title', 'Order Confirmation - ' . ($settings['cafe_name'] ?? 'CityLife Cafe'))

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-green-600 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <div class="bg-white bg-opacity-20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Order Confirmed!</h1>
            <p class="text-lg opacity-90">Thank you for your order. We'll have it ready for you soon.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="border-b pb-4 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Order Details</h2>
                    <p class="text-gray-600 mt-1">Order #{{ $order->order_number }}</p>
                </div>

                <!-- Customer Information -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Customer Information</h3>
                        <div class="space-y-1 text-sm">
                            <p><span class="text-gray-600">Name:</span> {{ $order->customer_name }}</p>
                            <p><span class="text-gray-600">Email:</span> {{ $order->customer_email }}</p>
                            @if($order->customer_phone)
                                <p><span class="text-gray-600">Phone:</span> {{ $order->customer_phone }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Order Information</h3>
                        <div class="space-y-1 text-sm">
                            <p><span class="text-gray-600">Type:</span> {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</p>
                            <p><span class="text-gray-600">Status:</span>
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </p>
                            <p><span class="text-gray-600">Order Date:</span> {{ $order->order_date->format('M j, Y g:i A') }}</p>
                            @if($order->scheduled_for)
                                <p><span class="text-gray-600">Pickup Time:</span> {{ $order->scheduled_for->format('M j, Y g:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Order Items</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex justify-between items-start border-b pb-3">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800">{{ $item->product->name }}</h4>
                                    @if($item->customizations)
                                        <p class="text-gray-600 text-sm mt-1">{{ $item->customizations }}</p>
                                    @endif
                                    <p class="text-gray-500 text-sm">£{{ number_format($item->unit_price, 2) }} x {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="font-semibold">£{{ number_format($item->total_price, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t pt-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>£{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->tax_amount > 0)
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>VAT:</span>
                                <span>£{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                        @endif
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-sm text-green-600">
                                <span>Discount:</span>
                                <span>-£{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span>Total:</span>
                            <span class="text-blue-600">£{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($order->notes)
                    <div class="mt-6 pt-4 border-t">
                        <h3 class="font-semibold text-gray-800 mb-2">Special Instructions</h3>
                        <p class="text-gray-600 text-sm">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-blue-800 mb-3">What happens next?</h3>
                <div class="space-y-2 text-blue-700 text-sm">
                    <div class="flex items-start">
                        <span class="bg-blue-200 text-blue-800 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                        <span>We'll start preparing your order right away</span>
                    </div>
                    <div class="flex items-start">
                        <span class="bg-blue-200 text-blue-800 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                        <span>
                            @if($order->order_type === 'dine_in')
                                Please take a seat and we'll bring your order to you
                            @else
                                Your order will be ready for pickup
                                @if($order->scheduled_for)
                                    at {{ $order->scheduled_for->format('g:i A') }}
                                @else
                                    in approximately {{ ($settings['order_ahead_time'] ?? 15) }} minutes
                                @endif
                            @endif
                        </span>
                    </div>
                    <div class="flex items-start">
                        <span class="bg-blue-200 text-blue-800 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                        <span>Payment can be made when you collect your order</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="text-center space-y-4">
                <a href="{{ route('cafe.menu') }}"
                   class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                    Order More Items
                </a>

                <div class="text-gray-600 text-sm">
                    <p>Questions about your order?</p>
                    @if(isset($settings['cafe_phone']))
                        <p>Call us at <a href="tel:{{ $settings['cafe_phone'] }}" class="text-blue-600 hover:underline">{{ $settings['cafe_phone'] }}</a></p>
                    @endif
                    @if(isset($settings['cafe_email']))
                        <p>Email us at <a href="mailto:{{ $settings['cafe_email'] }}" class="text-blue-600 hover:underline">{{ $settings['cafe_email'] }}</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
