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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('produk');
            $table->string('kodegrp');
            $table->unsignedBigInteger('kategori_id');
            $table->string('status');
            $table->integer('stock_awal');
            $table->integer('stock_sekarang');
            $table->string('kode_qr')->unique();
            $table->integer('line_divisi')->default(1);
            $table->date('production_date');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('deleted_at')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('main_produk')->nullable();

            // $table->foreign('kategori_id')->references('id')->on('kategori');
            // $table->foreign('created_by')->references('id')->on('user');
            // $table->foreign('updated_by')->references('id')->on('user')->nullable();
            // $table->foreign('main_produk')->references('id')->on('main_produk');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
