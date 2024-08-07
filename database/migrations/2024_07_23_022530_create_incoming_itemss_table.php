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
        Schema::create('incoming_items', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('employee_nip');
            $table->string('supplier_code');
            $table->text('note')->nullable();
            $table->integer('total_items')->default(0);

            $table->foreign('employee_nip')->references('nip')->on('employees');
            $table->foreign('supplier_code')->references('code')->on('suppliers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_items');
        Schema::dropIfExists('item_ins');
    }
};
