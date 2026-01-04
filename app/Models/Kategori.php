<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relasi ke vendor
    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }
}
