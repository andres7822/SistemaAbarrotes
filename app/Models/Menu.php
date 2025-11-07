<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    public function icono()
    {
        return $this->belongsTo(Icono::class);
    }

    public function tipo_menu()
    {
        return $this->belongsTo(TipoMenu::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function subs()
    {
        return $this->hasMany(Menu::class, 'menu_id')
            ->with('subs')
            ->orderBy('tipo_menu_id')
            ->orderBy('nombre');
    }

    protected $guarded = ['id'];
}
