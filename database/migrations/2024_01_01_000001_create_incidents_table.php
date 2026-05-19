<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->text('address');
            $table->text('description');
            $table->string('reporter');
            $table->enum('scale', ['Kecil', 'Sedang', 'Besar'])->default('Sedang');
            $table->enum('status', ['Laporan Baru', 'Dalam Penanganan', 'Selesai'])->default('Laporan Baru');
            $table->string('source')->default('Masyarakat');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidents');
    }
};