<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItemDetail extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $autoIncrement = false;

    public function requestItem()
    {
        return $this->belongsTo(RequestItem::class, 'request_item_code', 'code');
    }
}
