<x-app-layout>

@section('title', 'Order Online - ' . ($settings['cafe_name'] ?? 'CityLife Cafe'))

@section('meta_description', 'Place your order online at ' . ($settings['cafe_name'] ?? 'CityLife Cafe'))

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="page-header__title">Place Your Order</h2>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><i class="icon-home"></i> <a href="{{ route('cafe.menu') }}">Cafe</a></li>
            <li><span>Order</span></li>
        </ul>
    </div>
</section>

<!-- Order Page Start -->
<section class="cart-page section-space">
    <div class="container">
        <div class="row gutter-y-40">
            <!-- Menu Items -->
            <div class="col-xl-8">
                <h3 class="cart-page__title mb-4">Select Items</h3>

                @if($categories->count() > 0)
                    @foreach($categories as $category)
                        @if($category->products->count() > 0)
                            <div class="menu-category mb-5">
                                <div class="menu-category__header" onclick="toggleCategory('{{ $category->id }}')" style="cursor: pointer;">
                                    <h4 class="menu-category__title d-flex justify-content-between align-items-center">
                                        <span>{{ $category->name }}</span>
                                        <i class="fas fa-chevron-down category-arrow" id="arrow-{{ $category->id }}"></i>
                                    </h4>
                                    @if($category->description)
                                        <p class="menu-category__description">{{ $category->description }}</p>
                                    @endif
                                </div>
                                <div class="menu-category__content" id="category-{{ $category->id }}" style="display: none;">

                                <div class="table-responsive">
                                    <table class="table cart-page__table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Sub Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category->products as $product)
                                                <tr data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-price="{{ $product->price }}">
                                                    <td>
                                                        <div class="cart-page__table__meta">
                                                            @if($product->image)
                                                                <div class="cart-page__table__meta__img">
                                                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                                                </div>
                                                            @endif
                                                            <div class="cart-page__table__meta__content">
                                                                <h3 class="cart-page__table__meta__title">
                                                                    <a href="{{ route('cafe.product', [$category->slug, $product->slug]) }}">
                                                                        {{ $product->name }}
                                                                    </a>
                                                                </h3>
                                                                @if($product->description)
                                                                    <p class="cart-page__table__meta__description">
                                                                        {{ Str::limit($product->description, 60) }}
                                                                    </p>
                                                                @endif

                                                                <!-- Product Details -->
                                                                <div class="product-details-tags">
                                                                    @if($product->size)
                                                                        <span class="product-tag">{{ ucfirst($product->size) }}</span>
                                                                    @endif
                                                                    @if($product->preparation_time)
                                                                        <span class="product-tag">{{ $product->preparation_time }} min</span>
                                                                    @endif
                                                                    @if($product->dietary_info && count($product->dietary_info) > 0)
                                                                        @foreach($product->dietary_info as $diet)
                                                                            <span class="product-tag dietary">{{ ucfirst(str_replace('_', ' ', $diet)) }}</span>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="cart-page__table__price">£{{ number_format($product->price, 2) }}</td>
                                                    <td>
                                                        <div class="product-details__quantity">
                                                            <div class="quantity-box">
                                                                <button type="button" class="sub" onclick="decreaseQuantity({{ $product->id }})">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                                <input type="text" value="0" id="qty-{{ $product->id }}" readonly>
                                                                <button type="button" class="add" onclick="increaseQuantity({{ $product->id }})">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="cart-page__table__total" id="total-{{ $product->id }}">£0.00</td>
                                                    <td>
                                                        <button type="button" class="cart-page__table__remove" onclick="removeItem({{ $product->id }})">
                                                            <i class="fas fa-times"></i> Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <p class="text-muted">No menu items available at the moment.</p>
                        <a href="{{ route('cafe.menu') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-arrow-left"></span></div>
                            </div>
                            <span class="citylife-btn__text">Back to Menu</span>
                        </a>
                    </div>
                @endif

                <!-- Special Instructions -->
                <div class="cart-page__coupone">
                    <h5 class="cart-page__coupone__title">Special Instructions:</h5>
                    <div class="cart-page__coupone__box">
                        <textarea id="order-notes" placeholder="Any special requests or dietary requirements..."
                                  class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-xl-4">
                <div class="cart-page__cart-checkout">
                    <h4 class="cart-checkout__title">Order Summary</h4>

                    <div id="order-items-summary" class="order-items-list mb-4">
                        <p class="text-muted text-center py-3">No items added yet</p>
                    </div>

                    <ul class="cart-page__cart-total list-unstyled">
                        <li><span>Subtotal</span><span class="cart-page__cart-total__amount" id="subtotal">£0.00</span></li>
                        @if(isset($settings['tax_rate']) && $settings['tax_rate'] > 0)
                            <li><span>VAT ({{ $settings['tax_rate'] }}%)</span><span class="cart-page__cart-total__amount" id="tax-amount">£0.00</span></li>
                        @endif
                        <li class="cart-total-final"><span>Total</span><span class="cart-page__cart-total__amount" id="total">£0.00</span></li>
                    </ul>

                    <!-- Customer Information Form -->
                    <form id="order-form" action="{{ route('cafe.order.store') }}" method="POST" class="checkout-form">
                        @csrf
                        <input type="hidden" id="order-items-input" name="items" value="">

                        <h5 class="checkout-form__title">Customer Information</h5>

                        <div class="checkout-form__group">
                            <label for="customer_name">Full Name *</label>
                            <input type="text" id="customer_name" name="customer_name" required class="form-control">
                        </div>

                        <div class="checkout-form__group">
                            <label for="customer_email">Email Address *</label>
                            <input type="email" id="customer_email" name="customer_email" required class="form-control">
                        </div>

                        <div class="checkout-form__group">
                            <label for="customer_phone">Phone Number</label>
                            <input type="tel" id="customer_phone" name="customer_phone" class="form-control">
                        </div>

                        <div class="checkout-form__group">
                            <label for="order_type">Order Type *</label>
                            <select id="order_type" name="order_type" required class="form-control">
                                <option value="dine_in">Dine In</option>
                                <option value="takeaway">Takeaway</option>
                            </select>
                        </div>

                        <div class="checkout-form__group">
                            <label for="scheduled_for">Pickup Time (Optional)</label>
                            <input type="datetime-local" id="scheduled_for" name="scheduled_for"
                                   min="{{ now()->addMinutes(15)->format('Y-m-d\TH:i') }}" class="form-control">
                        </div>

                        <input type="hidden" id="notes" name="notes" value="">

                        <button type="submit" id="place-order-btn" disabled class="citylife-btn w-100">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Place Order</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.menu-category__title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
    border-bottom: 2px solid #e74c3c;
    padding-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.menu-category__header:hover .menu-category__title {
    color: #e74c3c;
}

.menu-category__content {
    transition: all 0.3s ease;
}

.category-arrow {
    transition: transform 0.3s ease;
    font-size: 1rem;
}

.category-arrow.rotated {
    transform: rotate(180deg);
}

.cart-page__table__remove {
    color: #e74c3c;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
    padding: 0;
}

.menu-category__description {
    color: #666;
    margin-bottom: 1.5rem;
    font-style: italic;
}

.cart-page__table__meta__description {
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.25rem;
}

.product-details-tags {
    margin-top: 0.5rem;
}

.product-tag {
    display: inline-block;
    background: #f8f9fa;
    color: #6c757d;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    margin-right: 0.25rem;
    margin-bottom: 0.25rem;
}

.product-tag.dietary {
    background: #d4edda;
    color: #155724;
}

.checkout-form__title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 1.5rem 0 1rem 0;
    color: #333;
}

.checkout-form__group {
    margin-bottom: 1rem;
}

.checkout-form__group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.checkout-form__group .form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.checkout-form__group .form-control:focus {
    outline: none;
    border-color: #e74c3c;
    box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
}

.order-items-list {
    max-height: 300px;
    overflow-y: auto;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.order-item:last-child {
    border-bottom: none;
}

.cart-total-final {
    border-top: 2px solid #e74c3c;
    padding-top: 1rem !important;
    font-weight: 600;
    font-size: 1.1rem;
}
</style>

<script>
let orderItems = {};
const taxRate = {{ $settings['tax_rate'] ?? 0 }} / 100;

function toggleCategory(categoryId) {
    const content = document.getElementById(`category-${categoryId}`);
    const arrow = document.getElementById(`arrow-${categoryId}`);
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        arrow.classList.add('rotated');
    } else {
        content.style.display = 'none';
        arrow.classList.remove('rotated');
    }
}

function increaseQuantity(productId) {
    const productRow = document.querySelector(`[data-product-id="${productId}"]`);
    const productName = productRow.getAttribute('data-product-name');
    const productPrice = parseFloat(productRow.getAttribute('data-product-price'));

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

function removeItem(productId) {
    if (orderItems[productId]) {
        delete orderItems[productId];
    }
    updateDisplay();
}

function updateDisplay() {
    // Update quantity displays and totals
    Object.keys(orderItems).forEach(productId => {
        const qtyInput = document.getElementById(`qty-${productId}`);
        const totalEl = document.getElementById(`total-${productId}`);

        if (qtyInput && totalEl) {
            qtyInput.value = orderItems[productId].quantity;
            const itemTotal = orderItems[productId].quantity * orderItems[productId].price;
            totalEl.textContent = `£${itemTotal.toFixed(2)}`;
        }
    });

    // Reset quantities for items not in order
    document.querySelectorAll('[data-product-id]').forEach(row => {
        const productId = row.getAttribute('data-product-id');
        if (!orderItems[productId]) {
            const qtyInput = document.getElementById(`qty-${productId}`);
            const totalEl = document.getElementById(`total-${productId}`);
            if (qtyInput) qtyInput.value = '0';
            if (totalEl) totalEl.textContent = '£0.00';
        }
    });

    updateOrderSummary();
}

function updateOrderSummary() {
    const orderSummaryEl = document.getElementById('order-items-summary');
    const subtotalEl = document.getElementById('subtotal');
    const taxAmountEl = document.getElementById('tax-amount');
    const totalEl = document.getElementById('total');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const orderItemsInput = document.getElementById('order-items-input');
    const notesInput = document.getElementById('notes');
    const orderNotesTextarea = document.getElementById('order-notes');

    let subtotal = 0;
    let html = '';

    const itemsArray = Object.values(orderItems).filter(item => item.quantity > 0);

    if (itemsArray.length === 0) {
        html = '<p class="text-muted text-center py-3">No items added yet</p>';
        placeOrderBtn.disabled = true;
    } else {
        itemsArray.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            html += `
                <div class="order-item">
                    <div>
                        <strong>${item.name}</strong><br>
                        <small class="text-muted">£${item.price.toFixed(2)} × ${item.quantity}</small>
                    </div>
                    <div><strong>£${itemTotal.toFixed(2)}</strong></div>
                </div>
            `;
        });
        placeOrderBtn.disabled = false;
    }

    orderSummaryEl.innerHTML = html;

    const taxAmount = subtotal * taxRate;
    const total = subtotal + taxAmount;

    subtotalEl.textContent = `£${subtotal.toFixed(2)}`;
    if (taxAmountEl) taxAmountEl.textContent = `£${taxAmount.toFixed(2)}`;
    totalEl.textContent = `£${total.toFixed(2)}`;

    // Update hidden inputs
    orderItemsInput.value = JSON.stringify(itemsArray);
    notesInput.value = orderNotesTextarea.value;
}

// Update notes when textarea changes
document.getElementById('order-notes').addEventListener('input', function() {
    updateDisplay();
});

// Initialize display
updateDisplay();
</script>
</x-app-layout>
