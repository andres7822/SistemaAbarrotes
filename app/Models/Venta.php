<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    public function tipo_venta()
    {
        return $this->belongsTo(TipoVenta::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }

    public function estatus_venta()
    {
        return $this->belongsTo(EstatusVenta::class);
    }

    public function venta_detalle()
    {
        return $this->hasMany(VentaDetalle::class);
    }


    protected $guarded = ['id'];
}
