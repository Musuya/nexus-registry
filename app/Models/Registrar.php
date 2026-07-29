<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registrar extends Model
{
    protected $fillable = [
        'name',
        'contact_id',
        'status',
    ];

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}