<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    protected $fillable = [
        'kategori_id',
        'kode_sub',
        'name',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function coa()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
