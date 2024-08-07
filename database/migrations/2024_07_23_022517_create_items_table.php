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
        Schema::create('items', function (Blueprint $table) {
            $table->string('name');
            $table->string('code')->primary();
            $table->string('merk');
            $table->string('unit');
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('price')->default(0);
            $table->string('category_code');
            $table->foreign('category_code')->references('code')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
