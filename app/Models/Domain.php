<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    protected $fillable = [
        'name',
        'registrar_id',
        'status',
        'expires_at',
        'nameservers',
        'registrant_contact_id',
    ];

    // This is the magic that fixes the JSON/Array crash!
    protected $casts = [
        'expires_at' => 'date',
        'nameservers' => 'array',
    ];

    public function registrar(): BelongsTo
    {
        return $this->belongsTo(Registrar::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
