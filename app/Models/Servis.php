<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['perangkat_id', 'teknisi_id', 'status', 'diagnosa', 'catatan', 'tanggal_masuk', 'tanggal_selesai'])]
class Servis extends Model
{
    protected $table = 'servis';

    const STATUS_ANTRI            = 'antri';
    const STATUS_DALAM_PENGERJAAN = 'dalam_pengerjaan';
    const STATUS_SELESAI          = 'selesai';

    protected static function booted(): void
    {
        static::creating(function (Servis $servis): void {
            $servis->tanggal_masuk ??= today();
            $servis->nomor_nota    ??= sprintf('BPC/%s/%04d', now()->format('Y/m'), now()->timestamp % 10000);
        });
    }

    public function perangkat(): BelongsTo
    {
        return $this->belongsTo(Perangkat::class);
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function transaksi(): HasOne
    {
        return $this->hasOne(Transaksi::class);
    }
}
