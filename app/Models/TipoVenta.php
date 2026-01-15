<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVenta extends Model
{
    use HasFactory;

    public function venta()
    {
        return $this->hasMany(Venta::class);
    }

    protected $fillable = ['nombre'];
}
