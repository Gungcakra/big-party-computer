<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['pelanggan_id', 'jenis_perangkat', 'merek', 'spesifikasi', 'keluhan', 'kelengkapan'])]
class Perangkat extends Model
{
    protected $table = 'perangkat';

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function servis(): HasOne
    {
        return $this->hasOne(Servis::class);
    }
}
