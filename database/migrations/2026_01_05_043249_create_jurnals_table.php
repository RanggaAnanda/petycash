<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');

            $table->foreignId('account_id')->constrained();

            $table->string('ref_type');
            // pemasukan | pengeluaran | penyesuaian | dll

            $table->unsignedBigInteger('ref_id');

            $table->string('no_bukti')->nullable();

            $table->string('keterangan');

            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);

            $table->foreignId('store_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
