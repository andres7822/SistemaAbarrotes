<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function producto()
    {
        return $this->hasMany(Producto::class);
    }

    public function tipo_descuento()
    {
        return $this->belongsTo(TipoDescuento::class);
    }

    protected $guarded = ['id'];
}
