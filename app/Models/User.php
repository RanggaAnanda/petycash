<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'store_id', 'role'];

    protected $hidden = ['password'];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::make($value)
        );
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
