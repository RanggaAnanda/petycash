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
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coa_id')
                ->constrained('accounts')
                ->cascadeOnDelete();

            $table->string('kode_kategori')->unique();
            $table->string('name');
            $table->enum('status', ['masuk', 'keluar']);
            $table->enum('has_child', ['ya', 'tidak'])->default('tidak');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};
