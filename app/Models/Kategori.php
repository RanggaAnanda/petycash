<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kategori extends Model
{
    protected $fillable = [
        'coa_id',
        'kode_kategori',
        'name',
        'status',
        'has_child'
    ];

    public function subKategoris()
    {
        return $this->hasMany(SubKategori::class);
    }
    public function coa()
    {
        return $this->belongsTo(Account::class, 'coa_id');
    }
}
