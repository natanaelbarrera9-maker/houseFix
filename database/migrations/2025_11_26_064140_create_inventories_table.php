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
       Schema::create('inventories', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100); // Nombre del artículo
    $table->text('description')->nullable(); // Descripción
    $table->unsignedInteger('quantity'); // Cantidad disponible
    $table->decimal('price', 8, 2); // Precio
    $table->string('image_path')->nullable(); // Ruta de la imagen subida (¡clave para la imagen!)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
