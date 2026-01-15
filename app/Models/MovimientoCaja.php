<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    use HasFactory;

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function tipo_movimiento()
    {
        return $this->belongsTo(TipoMovimiento::class);
    }

    public function tipo_ingreso()
    {
        return $this->belongsTo(TipoIngreso::class);
    }

    protected $guarded = ['id'];
}
