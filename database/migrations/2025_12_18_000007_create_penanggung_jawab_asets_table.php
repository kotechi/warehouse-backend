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
        Schema::create('penanggung_jawab_asets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('unit_eselon_ii_id');
            $table->string('nama_pic', 150);
            $table->string('nip', 50)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id', 'fk_pj_aset_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('unit_eselon_ii_id', 'fk_pj_aset_unit_es2')->references('id')->on('unit_eselon_iis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penanggung_jawab_asets');
    }
};
