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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('stock');
            $table->string('keterangan');
            $table->string('kode_qr');
            $table->date('production_date');
            $table->enum('type', ['Stock In', 'Stock Out']);
            $table->timestamps();
            $table->foreign('barang_id')->references('id')->on('barangs');   
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
