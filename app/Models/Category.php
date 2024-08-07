<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $autoIncrement = false;

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
