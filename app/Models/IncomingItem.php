<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $autoIncrement = false;

    public function details()
    {
        return $this->hasMany(IncomingItemDetail::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}
