<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\CitaStatusHistory;
use App\Models\User;

class Cita extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'veterinaria_id',
        'cliente_id',
        'mascota_id',
        'veterinario_id',
        'user_id',
        'fecha',
        'hora',
        'motivo',
        'estado',
        'confirmed_at',
        'canceled_at',
        'completed_at',
        'reminder_sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'date',
        'confirmed_at' => 'datetime',
        'canceled_at' => 'datetime',
        'completed_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    /**
     * Get the veterinaria that owns the cita.
     */
    public function veterinaria()
    {
        return $this->belongsTo(Veterinaria::class);
    }

    /**
     * Get the cliente that owns the cita.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get the mascota that owns the cita.
     */
    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    /**
     * Get the veterinario that owns the cita.
     */
    public function veterinario()
    {
        return $this->belongsTo(Veterinario::class);
    }

    /**
     * Get the user that scheduled the cita.
     */
    public function agendadoPor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the mascotas associated with the cita.
     */
    public function mascotas()
    {
        return $this->belongsToMany(Mascota::class, 'cita_mascota')
            ->withTimestamps();
    }

    /**
     * Get status history entries for the cita.
     */
    public function statusHistories()
    {
        return $this->hasMany(CitaStatusHistory::class);
    }

    /**
     * Get the servicios for the cita.
     */
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'cita_servicio')
            ->withPivot(['cantidad'])
            ->withTimestamps();
    }

    /**
     * Get the productos for the cita.
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'cita_producto')
            ->withPivot(['cantidad'])
            ->withTimestamps();
    }

    /**
     * Combined date + time accessor.
     */
    public function getStartsAtAttribute()
    {
        return Carbon::parse($this->fecha->format('Y-m-d') . ' ' . $this->hora);
    }

    /**
     * Build a WhatsApp reminder message using the configured template.
     */
    public function buildWhatsappReminderMessage(): string
    {
        $template = config('appointments.whatsapp_template');
        $mascotas = $this->mascotas->pluck('nombre')->filter()->values();

        if ($mascotas->isEmpty() && $this->mascota) {
            $mascotas = collect([$this->mascota->nombre]);
        }

        $replace = [
            '{cliente}' => trim($this->cliente->nombre . ' ' . $this->cliente->apellido),
            '{fecha}' => $this->fecha->format('d/m/Y'),
            '{hora}' => Carbon::parse($this->hora)->format('h:i A'),
            '{motivo}' => $this->motivo,
            '{mascotas}' => $mascotas->isEmpty() ? 'Sin mascota' : $mascotas->join(', '),
        ];

        return str_replace(array_keys($replace), array_values($replace), $template);
    }
}