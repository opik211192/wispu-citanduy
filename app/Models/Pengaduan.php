<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function lampirans()
    {
        return $this->hasMany(Lampiran::class);
    }

    public function uraianPihaks()
    {
        return $this->hasMany(Uraian_pihak::class);
    }

    public function identitasDiri()
    {
        return $this->hasOne(IdentitasDiri::class);
    }
}
