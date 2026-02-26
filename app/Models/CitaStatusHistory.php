<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CitaStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'cita_id',
        'from_status',
        'to_status',
        'changed_by',
        'note',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
