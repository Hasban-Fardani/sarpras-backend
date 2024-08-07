<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $autoIncrement = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function details(){
        return $this->hasMany(RequestItemDetail::class);
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}
