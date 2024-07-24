<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function item_in()
    {
        return $this->belongsTo(ItemIn::class);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
