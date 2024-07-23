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
        Schema::create('submission_item_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_item_id')->constrained('submission_items');
            $table->foreignId('item_id')->constrained('items');
            $table->integer('qty');
            $table->integer('qty_acc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_item_details');
    }
};
