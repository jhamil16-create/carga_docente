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
        Schema::create('reportes', function (Blueprint $table) {
            $table->id('reporte_id');
            $table->unsignedBigInteger('usuario_id')->notNull();
            $table->string('tipo_reporte', 50)->notNull();
            $table->date('fecha_inicio')->notNull();
            $table->date('fecha_fin')->notNull();
            $table->timestamp('fecha_generacion')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('ruta_archivo', 255)->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('usuario_id')->references('usuario_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
