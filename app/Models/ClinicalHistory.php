<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'veterinarian_id',
        'visit_date',
        'reason',
        'diagnosis',
        'treatment',
        'notes',
    ];

    /**
     * Get the pet that owns the clinical history.
     */
    public function pet()
    {
        return $this->belongsTo(Mascota::class, 'pet_id');
    }

    /**
     * Get the veterinarian associated with the clinical history.
     */
    public function veterinarian()
    {
        return $this->belongsTo(Veterinario::class, 'veterinarian_id');
    }
}
