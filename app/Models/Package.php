<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name', 'duration_days', 'price', 'features'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}