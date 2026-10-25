<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('helados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 120);
            $table->string('categoria', 40);
            $table->string('descripcion', 300)->nullable();
            $table->decimal('precio', 8, 2);
            $table->unsignedInteger('existencias')->default(0);
            $table->string('imagen_tipo', 40)->nullable();
            $table->timestamps();
        });

        // La foto va en la base de datos administrada: así no depende del disco del pod
        // y sobrevive a cada despliegue. MEDIUMBLOB admite hasta 16 MB (la app limita a 2 MB).
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE helados ADD imagen MEDIUMBLOB NULL AFTER existencias');
        } else {
            Schema::table('helados', fn (Blueprint $t) => $t->binary('imagen')->nullable());
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('helados');
    }
};
