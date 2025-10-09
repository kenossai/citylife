<?php

namespace App\Http\Controllers;

use App\Models\CafeCategory;
use App\Models\CafeProduct;
use App\Models\CafeOrder;
use App\Models\CafeSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CafeController extends Controller
{
    public function menu(): View
    {
        $categories = CafeCategory::active()
            ->with(['products' => function ($query) {
                $query->available()->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $settings = CafeSetting::public()->pluck('value', 'key');

        return view('cafe.menu', compact('categories', 'settings'));
    }

    public function category(string $slug): View
    {
        $category = CafeCategory::where('slug', $slug)
            ->where('is_active', true)
            ->with(['products' => function ($query) {
                $query->available()->orderBy('sort_order');
            }])
            ->firstOrFail();

        $settings = CafeSetting::public()->pluck('value', 'key');

        return view('cafe.category', compact('category', 'settings'));
    }

    public function product(string $categorySlug, string $productSlug): View
    {
        $category = CafeCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $product = CafeProduct::where('slug', $productSlug)
            ->where('category_id', $category->id)
            ->where('is_available', true)
            ->firstOrFail();

        $relatedProducts = CafeProduct::where('category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->limit(4)
            ->get();

        $settings = CafeSetting::public()->pluck('value', 'key');

        return view('cafe.product', compact('product', 'category', 'relatedProducts', 'settings'));
    }

    public function orderCreate(): View
    {
        $categories = CafeCategory::active()
            ->with(['products' => function ($query) {
                $query->available()->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $settings = CafeSetting::public()->pluck('value', 'key');

        // Check if online ordering is allowed
        $allowOnlineOrdering = $settings['allow_online_ordering'] ?? 'false';

        if ($allowOnlineOrdering !== 'true') {
            abort(404, 'Online ordering is currently unavailable');
        }

        return view('cafe.order', compact('categories', 'settings'));
    }

    public function orderStore(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:cafe_products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.customizations' => 'nullable|string|max:500',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'order_type' => 'required|in:dine_in,takeaway',
            'scheduled_for' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Create or find customer (member)
        $member = \App\Models\Member::firstOrCreate(
            ['email' => $request->customer_email],
            [
                'name' => $request->customer_name,
                'phone' => $request->customer_phone,
            ]
        );

        // Calculate totals
        $subtotal = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = CafeProduct::findOrFail($item['product_id']);
            $itemTotal = $product->price * $item['quantity'];
            $subtotal += $itemTotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'total_price' => $itemTotal,
                'customizations' => $item['customizations'] ?? null,
            ];
        }

        $taxRate = (float) (CafeSetting::where('key', 'tax_rate')->value('value') ?? 0);
        $taxAmount = $subtotal * ($taxRate / 100);
        $totalAmount = $subtotal + $taxAmount;

        // Create order
        $order = CafeOrder::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'member_id' => $member->id,
            'customer_name' => $member->name,
            'customer_email' => $member->email,
            'customer_phone' => $member->phone,
            'order_status' => 'pending',
            'payment_status' => 'pending',
            'order_type' => $request->order_type,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'notes' => $request->notes,
            'special_instructions' => $request->notes,
            'scheduled_for' => $request->scheduled_for,
            'order_date' => now(),
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return redirect()->route('cafe.order.confirmation', $order->order_number)
            ->with('success', 'Your order has been placed successfully!');
    }

    public function orderConfirmation(string $orderNumber): View
    {
        $order = CafeOrder::where('order_number', $orderNumber)
            ->with(['items.product', 'member'])
            ->firstOrFail();

        $settings = CafeSetting::public()->pluck('value', 'key');

        return view('cafe.order-confirmation', compact('order', 'settings'));
    }

    public function receipt(CafeOrder $order): View
    {
        $order->load(['items.product', 'member']);
        $settings = CafeSetting::public()->pluck('value', 'key');

        return view('cafe.receipt', compact('order', 'settings'));
    }
}
