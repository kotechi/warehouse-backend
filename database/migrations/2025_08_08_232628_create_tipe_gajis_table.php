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
        Schema::create('tipe_gajis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tipe_gaji');
            $table->string('short_code');
            $table->string('tipe_gaji');
            $table->string('tipe_gaji2');   
            $table->string('tipe_pekerjaan');
            $table->string('tag_class');
            $table->integer('short');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipe_gajis');
    }
};
