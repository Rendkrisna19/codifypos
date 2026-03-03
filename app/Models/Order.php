<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    protected $fillable = [
        'tenant_id', 'user_id', 'order_number', 'order_type', 
        'subtotal', 'tax_amount', 'grand_total', 
        'amount_tendered', 'change_amount', 'payment_method'
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function cashier() {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted() {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }
}