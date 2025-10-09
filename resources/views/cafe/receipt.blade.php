@extends('layouts.app')

@section('title', 'Receipt - Order #' . $order->order_number)

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Receipt Header -->
            <div class="bg-blue-600 text-white p-6 text-center">
                <h1 class="text-2xl font-bold mb-2">{{ $settings['cafe_name'] ?? 'CityLife Cafe' }}</h1>
                <p class="opacity-90">{{ $settings['cafe_description'] ?? 'Thank you for your order!' }}</p>
            </div>

            <!-- Receipt Content -->
            <div class="p-6">
                <!-- Order Info -->
                <div class="text-center mb-6 pb-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Receipt</h2>
                    <p class="text-gray-600">Order #{{ $order->order_number }}</p>
                    <p class="text-gray-600 text-sm">{{ $order->order_date->format('M j, Y g:i A') }}</p>
                </div>

                <!-- Customer & Order Details -->
                <div class="grid md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Customer</h3>
                        <div class="text-sm space-y-1">
                            <p>{{ $order->customer_name }}</p>
                            <p>{{ $order->customer_email }}</p>
                            @if($order->customer_phone)
                                <p>{{ $order->customer_phone }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Order Details</h3>
                        <div class="text-sm space-y-1">
                            <p>Type: {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</p>
                            <p>Status: <span class="bg-{{ $order->order_status === 'completed' ? 'green' : 'yellow' }}-100 text-{{ $order->order_status === 'completed' ? 'green' : 'yellow' }}-800 px-2 py-1 rounded text-xs">{{ ucfirst($order->order_status) }}</span></p>
                            @if($order->scheduled_for)
                                <p>Scheduled: {{ $order->scheduled_for->format('M j, Y g:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Items</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800">{{ $item->product->name }}</h4>
                                    @if($item->customizations)
                                        <p class="text-gray-600 text-sm">{{ $item->customizations }}</p>
                                    @endif
                                    <p class="text-gray-500 text-sm">£{{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="font-semibold">£{{ number_format($item->total_price, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Totals -->
                <div class="border-t pt-4 mb-6">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>£{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->tax_amount > 0)
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>VAT ({{ ($settings['tax_rate'] ?? 20) }}%):</span>
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
                            <span>Total Paid:</span>
                            <span>£{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="mb-6 pb-6 border-b">
                    <h3 class="font-semibold text-gray-800 mb-2">Payment</h3>
                    <div class="text-sm">
                        <p>Status: <span class="bg-{{ $order->payment_status === 'paid' ? 'green' : 'yellow' }}-100 text-{{ $order->payment_status === 'paid' ? 'green' : 'yellow' }}-800 px-2 py-1 rounded text-xs">{{ ucfirst($order->payment_status) }}</span></p>
                        @if($order->payment_method)
                            <p>Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        @endif
                    </div>
                </div>

                @if($order->notes)
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="font-semibold text-gray-800 mb-2">Notes</h3>
                        <p class="text-gray-600 text-sm">{{ $order->notes }}</p>
                    </div>
                @endif

                <!-- Footer -->
                <div class="text-center text-gray-600 text-sm">
                    @if(isset($settings['cafe_phone']))
                        <p>{{ $settings['cafe_phone'] }}</p>
                    @endif
                    @if(isset($settings['cafe_email']))
                        <p>{{ $settings['cafe_email'] }}</p>
                    @endif
                    <p class="mt-4">Thank you for visiting {{ $settings['cafe_name'] ?? 'CityLife Cafe' }}!</p>
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="text-center mt-6">
            <button onclick="window.print()"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 no-print">
                Print Receipt
            </button>
            <a href="{{ route('cafe.menu') }}"
               class="ml-4 bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-300 no-print">
                Back to Menu
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }

    body {
        background: white !important;
    }

    .bg-gray-50 {
        background: white !important;
    }

    .shadow-lg {
        box-shadow: none !important;
    }
}
</style>
@endsection
