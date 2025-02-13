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
        Schema::table('tblProductData', function (Blueprint $table) {
            // Структура згідно записам csv
            $table->decimal('stock', 10, 2)->after('strProductCode');
            $table->integer('price')->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblProductData', function (Blueprint $table) {
            $table->dropColumn(['price', 'stock']);
        });
    }
};
