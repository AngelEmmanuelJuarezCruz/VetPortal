<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = ['uuid', 'name', 'email', 'phone', 'address', 'theme'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}