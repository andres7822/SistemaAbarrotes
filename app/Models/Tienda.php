<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tienda extends Model
{
    use HasFactory;

    public function bodega()
    {
        return $this->hasMany(Bodega::class);
    }

    public function handleUploadImage($Image)
    {
        $File = $Image;
        $Name = time() . $File->getClientOriginalName();
        //$File->move(public_path() . '/img/Productos/', $Name);
        Storage::putFileAs('/public/tiendas', $File, $Name, 'public');
        return $Name;
    }

    protected $guarded = ['id'];
}
