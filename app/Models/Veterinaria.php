<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinaria extends Model
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
        'correo',
        'telefono',
        'direccion',
        'logo',
        'tema',
    ];

    /**
     * Get the veterinarios for the veterinaria.
     */
    public function veterinarios()
    {
        return $this->hasMany(Veterinario::class);
    }

    /**
     * Get the clientes for the veterinaria.
     */
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}