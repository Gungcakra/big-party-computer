<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['servis_id', 'admin_id', 'biaya_jasa', 'biaya_sparepart', 'total', 'catatan', 'tanggal_bayar'])]
class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected function casts(): array
    {
        return [
            'biaya_jasa'      => 'decimal:2',
            'biaya_sparepart' => 'decimal:2',
            'total'           => 'decimal:2',
            'tanggal_bayar'   => 'date',
        ];
    }

    public function servis(): BelongsTo
    {
        return $this->belongsTo(Servis::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
