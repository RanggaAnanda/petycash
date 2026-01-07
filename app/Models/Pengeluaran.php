<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kategori_id',
        'sub_kategori_id',
        'nominal',
        'keterangan',
        'user_id',
        'store_id'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class);
    }

    public function jurnals()
    {
        return $this->hasMany(Jurnal::class, 'ref_id')
            ->where('ref_type', 'pengeluaran');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
