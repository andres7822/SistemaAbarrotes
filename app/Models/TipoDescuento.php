<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDescuento extends Model
{
    use HasFactory;

    public function categoria()
    {
        return $this->hasMany(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->hasMany(Subcategoria::class);
    }

    protected $fillable = ['id'];
}
