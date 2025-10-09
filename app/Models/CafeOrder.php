<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CafeOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'member_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'order_type',
        'table_number',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'special_instructions',
        'order_date',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'order_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = static::generateOrderNumber();
            }
            
            if (!$order->order_date) {
                $order->order_date = now();
            }
        });
    }

    /**
     * Get the member that owns the order.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the order items for this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CafeOrderItem::class, 'order_id');
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'CAFE';
        $date = now()->format('Ymd');
        $sequence = static::whereDate('created_at', today())->count() + 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('order_status', $status);
    }

    /**
     * Scope a query to filter by payment status.
     */
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope a query to filter today's orders.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('order_date', today());
    }

    /**
     * Scope a query to filter this week's orders.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('order_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope a query to filter this month's orders.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('order_date', now()->month)
                    ->whereYear('order_date', now()->year);
    }

    /**
     * Get the formatted total amount.
     */
    public function getFormattedTotalAttribute(): string
    {
        return '£' . number_format($this->total_amount, 2);
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->order_status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'served' => 'secondary',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the payment status badge color.
     */
    public function getPaymentStatusColorAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'warning',
            'paid' => 'success',
            'refunded' => 'info',
            'failed' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Check if order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->order_status, ['pending', 'confirmed']);
    }

    /**
     * Check if order can be refunded.
     */
    public function canBeRefunded(): bool
    {
        return $this->payment_status === 'paid' && 
               in_array($this->order_status, ['served', 'cancelled']);
    }

    /**
     * Calculate total items count.
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Update order status.
     */
    public function updateStatus(string $status): void
    {
        $this->update(['order_status' => $status]);
        
        if ($status === 'served') {
            $this->update(['completed_at' => now()]);
        }
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(string $status): void
    {
        $this->update(['payment_status' => $status]);
    }
}
