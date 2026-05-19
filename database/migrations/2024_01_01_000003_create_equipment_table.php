<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['APAR', 'Kendaraan', 'APD', 'Alat Pemotong']);
            $table->integer('quantity');
            $table->enum('status', ['Baik', 'Perbaikan', 'Rusak'])->default('Baik');
            $table->date('last_service')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment');
    }
};