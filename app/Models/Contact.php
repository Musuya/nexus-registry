<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function registrars(): HasMany
    {
        return $this->hasMany(Registrar::class);
    }
}