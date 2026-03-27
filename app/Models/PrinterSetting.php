<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PrinterSetting extends Model
{
    protected $fillable = [
        'tenant_id', 'paper_size', 'printer_name', 'header_text', 'footer_text', 'auto_print'
    ];

    protected static function booted() {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }
}