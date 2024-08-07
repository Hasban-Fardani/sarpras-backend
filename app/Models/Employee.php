<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function getRouteKeyName()
    {
        return 'nip';
    }
}
