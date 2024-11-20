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
        Schema::disableForeignKeyConstraints();

        Schema::create('stock_differences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained();
            $table->year('year');
            $table->integer('saldo_akhir_qty');
            $table->integer('opname_qty');
            $table->integer('difference_qty');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_differences');
    }
};
