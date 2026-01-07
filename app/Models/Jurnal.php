<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'account_id',
        'ref_type',
        'ref_id',
        'no_bukti',
        'keterangan',
        'debit',
        'kredit',
        'store_id',
        'kategori_id',
        'subkategori_id',
    ];

    // ✅ RELASI KE ACCOUNT
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    // Relasi ke store (opsional)
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Polymorphic relation (pemasukan / pengeluaran)
    public function ref()
    {
        return $this->morphTo();
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class);
    }
}
