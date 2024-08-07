<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $autoIncrement = false;
}
