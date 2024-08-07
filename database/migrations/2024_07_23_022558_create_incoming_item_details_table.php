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
        Schema::create('outgoing_items_details', function (Blueprint $table) {
            $table->string('incoming_item_code');
            $table->string('item_code');
            $table->integer('qty');
            
            $table->foreign('incoming_item_code')->references('code')->on('incoming_items')->cascadeOnDelete();
            $table->foreign('item_code')->references('code')->on('items')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_item_details');
        Schema::dropIfExists('item_in_details');
    }
};
