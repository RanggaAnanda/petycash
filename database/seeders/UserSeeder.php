<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'Admin@planetfashion.id',
                'password' => Hash::make('12345678'),
                'role' => 'superadmin',
                'store_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rangga',
                'email' => 'Rangga@planetfashion.id',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'store_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nanda',
                'email' => 'Nanda@planetfashion.id',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'store_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
