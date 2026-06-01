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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat');
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->foreignId('warga_id')->constrained('warga')->cascadeOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('petugas')->nullOnDelete();
            $table->foreignId('ocr_id')->constrained('ocrs')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
