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
            $table->string('kategori_id');
            $table->string('status');
            $table->string('main_produk');
            $table->integer('stock_awal')->default(0);
            $table->integer('stock_sekarang')->default(0);
            $table->string('kode_qr')->unique();
            $table->string('line_divisi');
            $table->date('production_date');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes(); // kolom deleted_at
            $table->timestamps();  // kolom created_at & updated_at
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
