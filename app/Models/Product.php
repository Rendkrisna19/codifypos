<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'tenant_id', 'category_id', 'name', 'sku', 
        'cost_price', 'selling_price', 'stock', 'image', 'is_active'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    protected static function booted() {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }
}