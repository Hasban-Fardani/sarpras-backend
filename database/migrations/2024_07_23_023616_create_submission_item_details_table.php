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
            $table->string('submission_item_code');
            $table->string('item_code');
            $table->foreign('submission_item_code')->references('code')->on('submission_items')->cascadeOnDelete();
            $table->foreign('item_code')->references('code')->on('items')->cascadeOnDelete();
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
