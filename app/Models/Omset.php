<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Omset extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data
    protected $fillable = [
        'tanggal',
        'nominal',
        'store_id',
        'keterangan',
        'user_id'
    ];

    /**
     * Relasi ke model Store
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
