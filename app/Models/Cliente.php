<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'nombre',
        'apellido',
        'telefono',
        'correo',
        'veterinaria_id',
    ];

    /**
     * Get the mascotas for the cliente.
     */
    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }

    /**
     * Get the veterinaria that owns the cliente.
     */
    public function veterinaria()
    {
        return $this->belongsTo(Veterinaria::class);
    }
}