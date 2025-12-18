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
        Schema::create('unit_eselon_iis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('satker_id');
            $table->string('kode_unit', 50)->unique();
            $table->string('nama_unit', 150);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('satker_id', 'fk_unit_es2_satker')->references('id')->on('satkers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_eselon_iis');
    }
};
