<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinario extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'veterinaria_id',
        'nombre',
        'especialidad',
        'telefono',
        'correo',
        'activo',
    ];

    /**
     * Get the veterinaria that owns the veterinario.
     */
    public function veterinaria()
    {
        return $this->belongsTo(Veterinaria::class);
    }

    /**
     * Get the citas for the veterinario.
     */
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}