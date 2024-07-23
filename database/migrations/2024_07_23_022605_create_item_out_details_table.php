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
        Schema::create('item_out_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_out_id')->constrained('item_outs')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_out_details');
    }
};
