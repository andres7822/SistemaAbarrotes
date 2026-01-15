<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroAccione extends Model
{
    use HasFactory;

    public function accion()
    {
        return $this->belongsTo(Accione::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $guarded = ['id'];
}
