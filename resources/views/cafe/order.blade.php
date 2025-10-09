<x-app-layout>

@section('title', 'Order Online - ' . ($settings['cafe_name'] ?? 'CityLife Cafe'))

@section('meta_description', 'Place your order online at ' . ($settings['cafe_name'] ?? 'CityLife Cafe'))

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Place Your Order</h1>
            <p class="text-lg opacity-90">Select your items and we'll have them ready for you</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Menu Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Menu</h2>

                    @if($categories->count() > 0)
                        <div class="space-y-8">
                            @foreach($categories as $category)
                                @if($category->products->count() > 0)
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                                            {{ $category->name }}
                                        </h3>
                                        <div class="space-y-4">
                                            @foreach($category->products as $product)
                                                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition duration-300"
                                                     data-product-id="{{ $product->id }}"
                                                     data-product-name="{{ $product->name }}"
                                                     data-product-price="{{ $product->price }}">
                                                    <div class="flex-1">
                                                        <div class="flex items-start justify-between">
                                                            <div>
                                                                <h4 class="font-semibold text-gray-800">{{ $product->name }}</h4>
                                                                @if($product->description)
                                                                    <p class="text-gray-600 text-sm mt-1">{{ $product->description }}</p>
                                                                @endif

                                                                <!-- Product Details -->
                                                                <div class="flex flex-wrap gap-2 mt-2">
                                                                    @if($product->size)
                                                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{{ ucfirst($product->size) }}</span>
                                                                    @endif
                                                                    @if($product->preparation_time)
                                                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{{ $product->preparation_time }} min</span>
                                                                    @endif
                                                                </div>

                                                                <!-- Dietary Info -->
                                                                @if($product->dietary_info && count($product->dietary_info) > 0)
                                                                    <div class="flex flex-wrap gap-1 mt-2">
                                                                        @foreach($product->dietary_info as $diet)
                                                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">{{ ucfirst(str_replace('_', ' ', $diet)) }}</span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="text-right ml-4">
                                                                <span class="text-lg font-bold text-blue-600">£{{ number_format($product->price, 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="ml-4 flex items-center space-x-2">
                                                        <button type="button"
                                                                class="decrease-qty bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center"
                                                                onclick="decreaseQuantity({{ $product->id }})">
                                                            -
                                                        </button>
                                                        <span class="quantity-display w-8 text-center font-semibold" id="qty-{{ $product->id }}">0</span>
                                                        <button type="button"
                                                                class="increase-qty bg-blue-600 hover:bg-blue-700 text-white w-8 h-8 rounded-full flex items-center justify-center"
                                                                onclick="increaseQuantity({{ $product->id }})">
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600">No menu items available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Your Order</h2>

                    <div id="order-items" class="space-y-3 mb-4">
                        <p class="text-gray-500 text-center py-4">No items added yet</p>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold">Subtotal:</span>
                            <span id="subtotal" class="font-semibold">£0.00</span>
                        </div>
                        @if(isset($settings['tax_rate']) && $settings['tax_rate'] > 0)
                            <div class="flex justify-between items-center mb-2 text-sm text-gray-600">
                                <span>VAT ({{ $settings['tax_rate'] }}%):</span>
                                <span id="tax-amount">£0.00</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-lg font-bold border-t pt-2">
                            <span>Total:</span>
                            <span id="total" class="text-blue-600">£0.00</span>
                        </div>
                    </div>

                    <form id="order-form" action="{{ route('cafe.order.store') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" id="order-items-input" name="items" value="">

                        <div class="space-y-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="customer_name" name="customer_name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" id="customer_email" name="customer_email" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="tel" id="customer_phone" name="customer_phone"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="order_type" class="block text-sm font-medium text-gray-700 mb-1">Order Type *</label>
                                <select id="order_type" name="order_type" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="dine_in">Dine In</option>
                                    <option value="takeaway">Takeaway</option>
                                </select>
                            </div>

                            <div>
                                <label for="scheduled_for" class="block text-sm font-medium text-gray-700 mb-1">Pickup Time (Optional)</label>
                                <input type="datetime-local" id="scheduled_for" name="scheduled_for"
                                       min="{{ now()->addMinutes(15)->format('Y-m-d\TH:i') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Any special requests or dietary requirements..."></textarea>
                            </div>
                        </div>

                        <button type="submit" id="place-order-btn" disabled
                                class="w-full mt-6 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition duration-300">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let orderItems = {};
const taxRate = {{ $settings['tax_rate'] ?? 0 }} / 100;

function increaseQuantity(productId) {
    const productEl = document.querySelector(`[data-product-id="${productId}"]`);
    const productName = productEl.getAttribute('data-product-name');
    const productPrice = parseFloat(productEl.getAttribute('data-product-price'));

    if (!orderItems[productId]) {
        orderItems[productId] = {
            product_id: productId,
            name: productName,
            price: productPrice,
            quantity: 0
        };
    }

    orderItems[productId].quantity++;
    updateDisplay();
}

function decreaseQuantity(productId) {
    if (orderItems[productId] && orderItems[productId].quantity > 0) {
        orderItems[productId].quantity--;
        if (orderItems[productId].quantity === 0) {
            delete orderItems[productId];
        }
    }
    updateDisplay();
}

function updateDisplay() {
    // Update quantity displays
    Object.keys(orderItems).forEach(productId => {
        const qtyEl = document.getElementById(`qty-${productId}`);
        if (qtyEl) {
            qtyEl.textContent = orderItems[productId].quantity;
        }
    });

    // Reset quantities for items not in order
    document.querySelectorAll('.quantity-display').forEach(el => {
        const productId = el.id.replace('qty-', '');
        if (!orderItems[productId]) {
            el.textContent = '0';
        }
    });

    // Update order summary
    updateOrderSummary();
}

function updateOrderSummary() {
    const orderItemsEl = document.getElementById('order-items');
    const subtotalEl = document.getElementById('subtotal');
    const taxAmountEl = document.getElementById('tax-amount');
    const totalEl = document.getElementById('total');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const orderItemsInput = document.getElementById('order-items-input');

    let subtotal = 0;
    let html = '';

    const itemsArray = Object.values(orderItems).filter(item => item.quantity > 0);

    if (itemsArray.length === 0) {
        html = '<p class="text-gray-500 text-center py-4">No items added yet</p>';
        placeOrderBtn.disabled = true;
    } else {
        itemsArray.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            html += `
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-medium">${item.name}</span>
                        <span class="text-gray-500 text-sm block">£${item.price.toFixed(2)} x ${item.quantity}</span>
                    </div>
                    <span class="font-semibold">£${itemTotal.toFixed(2)}</span>
                </div>
            `;
        });
        placeOrderBtn.disabled = false;
    }

    orderItemsEl.innerHTML = html;

    const taxAmount = subtotal * taxRate;
    const total = subtotal + taxAmount;

    subtotalEl.textContent = `£${subtotal.toFixed(2)}`;
    if (taxAmountEl) taxAmountEl.textContent = `£${taxAmount.toFixed(2)}`;
    totalEl.textContent = `£${total.toFixed(2)}`;

    // Update hidden input with order items
    orderItemsInput.value = JSON.stringify(itemsArray);
}

// Initialize display
updateDisplay();
</script>
</x-app-layout>
