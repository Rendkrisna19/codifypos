<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = ['tenant_id', 'name'];

    public function products() {
        return $this->hasMany(Product::class);
    }

    // Global Scope: Otomatis memfilter data berdasarkan Tenant Kasir yang sedang login
    protected static function booted() {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }
}