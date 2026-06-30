<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('gambar', 255)->nullable();
            $table->string('nama_produk', 200);
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->decimal('harga', 12, 2);
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
