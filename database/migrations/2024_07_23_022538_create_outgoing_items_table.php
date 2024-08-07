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
        Schema::create('outgoing_items', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('operator_nip');
            $table->string('division_nip');
            $table->integer('total_items')->default(0);
            $table->text('note')->nullable();

            $table->foreign('operator_nip')->references('nip')->on('employees');
            $table->foreign('division_nip')->references('nip')->on('employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing_items');
        Schema::dropIfExists('item_outs');
    }
};
