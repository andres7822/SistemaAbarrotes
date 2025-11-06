<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icono extends Model
{
    use HasFactory;

    public function menu()
    {
        return $this->hasMany(Menu::class);
    }

    public $fillable = ['nombre'];
}
