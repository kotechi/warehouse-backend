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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('old_values');
            $table->unsignedBigInteger('new_values');
            $table->unsignedBigInteger('input_values');
            $table->string('deskripsi')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // $table->foreign('user_id')->references('id')->on('user');
            // $table->foreign('produk_id')->references('id')->on('ref_produk');
            // $table->foreign('updated_by')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
