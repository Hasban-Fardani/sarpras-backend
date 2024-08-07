<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $autoIncrement = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function hasSufficientStock(int $amount): bool
    {
        return $this->stock >= $amount;
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}
