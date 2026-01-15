<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMovimiento extends Model
{
    use HasFactory;

    public function movimiento_caja()
    {
        return $this->hasMany(MovimientoCaja::class);
    }

    protected $fillable = ['nombre'];
}
