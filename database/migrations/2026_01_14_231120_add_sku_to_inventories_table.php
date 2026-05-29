<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('inventories', function (Blueprint $table) {
        // Agregamos SKU después del nombre, puede ser nulo, y debe ser único
        $table->string('sku', 50)->nullable()->unique()->after('name');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('inventories', function (Blueprint $table) {
        $table->dropColumn('sku');
    });
}
};  