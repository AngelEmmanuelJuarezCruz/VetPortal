<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'veterinaria_id',
        'tenant_id',
        'nombre',
        'tipo',
        'categoria',
        'descripcion',
        'stock_actual',
        'stock_minimo',
        'precio_compra',
        'precio_venta',
        'proveedor',
        'fecha_caducidad',
    ];

    protected $casts = [
        'fecha_caducidad' => 'date',
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
    ];

    public function veterinaria()
    {
        return $this->belongsTo(Veterinaria::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function movimientosStock()
    {
        return $this->hasMany(MovimientoStock::class);
    }

    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'cita_producto')
            ->withPivot(['cantidad'])
            ->withTimestamps();
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_actual <= $this->stock_minimo;
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        if (!$this->fecha_caducidad) {
            return false;
        }

        return $this->fecha_caducidad->isBetween(now(), now()->addDays(30));
    }
}
