<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOutDetail extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $autoIncrement = false;

    public function getRouteKeyName()
    {
        return 'code';
    }
}
