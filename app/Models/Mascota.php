<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'descripcion',
        'foto',
        'color',
        'fecha_nacimiento',
        'cliente_id',
        'veterinaria_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Get the cliente that owns the mascota.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get the citas for the mascota.
     */
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    /**
     * Get the citas linked through the pivot table.
     */
    public function citasMultiples()
    {
        return $this->belongsToMany(Cita::class, 'cita_mascota')
            ->withTimestamps();
    }

    /**
     * Get the clinical history for the pet.
     */
    public function clinicalHistories()
    {
        return $this->hasMany(ClinicalHistory::class, 'pet_id');
    }

    /**
     * Get the age of the pet in years.
     */
    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }
        return now()->diffInYears($this->fecha_nacimiento);
    }

    /**
     * Get the formatted age.
     */
    public function getEdadFormattedAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return 'Sin registro';
        }

        $now = now();
        $years = $now->diffInYears($this->fecha_nacimiento);
        $mascota_birth = $this->fecha_nacimiento->copy()->addYears($years);
        $months = $mascota_birth->diffInMonths($now);

        if ($years > 0) {
            return "$years año" . ($years > 1 ? 's' : '') . ($months > 0 ? " y $months mes" . ($months > 1 ? 'es' : '') : '');
        }

        return "$months mes" . ($months > 1 ? 'es' : '');
    }
}