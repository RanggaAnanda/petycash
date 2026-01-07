<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun')->unique();
            $table->enum('jenis_akun', ['Aset', 'Kewajiban', 'Modal', 'Pendapatan', 'Beban']);
            $table->enum('normal_balance', ['Debit', 'Kredit']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
