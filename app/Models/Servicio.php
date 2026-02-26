<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'veterinaria_id',
        'tenant_id',
        'nombre',
        'categoria',
        'descripcion',
        'duracion_estimada',
        'precio',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function veterinaria()
    {
        return $this->belongsTo(Veterinaria::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'cita_servicio')
            ->withPivot(['cantidad'])
            ->withTimestamps();
    }
}
