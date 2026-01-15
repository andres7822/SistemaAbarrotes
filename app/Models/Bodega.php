<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    use HasFactory;

    public function inventario()
    {
        return $this->hasMany(Inventario::class);
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }

    protected $fillable = ['nombre', 'ubicacion', 'tienda_id'];
}
