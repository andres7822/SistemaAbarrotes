<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusVenta extends Model
{
    use HasFactory;

    public function venta()
    {
        return $this->hasMany(Venta::class);
    }

    protected $guarded = ['id'];
}
