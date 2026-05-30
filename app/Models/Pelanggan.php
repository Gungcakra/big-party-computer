<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'telepon', 'alamat'])]
class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    public function perangkat(): HasMany
    {
        return $this->hasMany(Perangkat::class);
    }
}
