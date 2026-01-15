<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function presentacione()
    {
        return $this->belongsTo(Presentacione::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    protected $fillable = ['nombre', 'codigo_barras', 'descripcion', 'precio_venta', 'costo', 'imagen', 'estado',
        'marca_id', 'presentacione_id', 'subcategoria_id'];

    public function handleUploadImage($Image)
    {
        $File = $Image;
        $Name = time() . $File->getClientOriginalName();
        //$File->move(public_path() . '/img/Productos/', $Name);
        Storage::putFileAs('/public/productos', $File, $Name, 'public');
        return $Name;
    }
}
