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
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('kodejob');
            $table->string('jabatan');
            $table->unsignedBigInteger('tipe_gaji_id');
            $table->integer('short');
            $table->timestamps();

            // $table->foreign('tipe_gaji_id')->references('id')->on('tipe_gaji');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
