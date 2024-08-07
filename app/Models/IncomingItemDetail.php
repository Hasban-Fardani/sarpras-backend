<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingItemDetail extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $autoIncrement = false;

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function incoming_item()
    {
        return $this->belongsTo(IncomingItem::class);
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}
