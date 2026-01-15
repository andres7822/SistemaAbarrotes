<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accione extends Model
{
    use HasFactory;

    public function registro_acciones()
    {
        return $this->hasMany(RegistroAccione::class);
    }

    protected $fillable = ['nombre'];
}
