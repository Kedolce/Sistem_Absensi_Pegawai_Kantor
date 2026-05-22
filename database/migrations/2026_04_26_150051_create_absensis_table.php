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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');

            $table->time('jam_masuk')->nullable();
            $table->string('foto_masuk')->nullable(); // TAMBAHAN

            $table->time('jam_keluar')->nullable();
            $table->string('foto_keluar')->nullable(); // TAMBAHAN

            $table->enum('status', [
                'hadir',
                'izin',
                'sakit',
                'alpha',
                'telat',
                'pulang_cepat'
            ])->default('alpha');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
