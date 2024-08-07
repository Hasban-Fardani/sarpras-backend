<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $autoIncrement = false;

    public function operator() 
    {
        return $this->belongsTo(Employee::class, 'operator_nip');
    }

    public function division() 
    {
        return $this->belongsTo(Employee::class, 'division_nip');
    }

    public function details()
    {
        return $this->hasMany(ItemOutDetail::class);
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}
