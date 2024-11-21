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
        Schema::table('stocks', function (Blueprint $table) {
            // Add location_id as a foreign key
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null')->after('item_id');
        
            // Drop the old location string column
            $table->dropColumn('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            
            // Add the old location string column
            $table->string('location', 50)->after('item_id');
        });
    }
};
