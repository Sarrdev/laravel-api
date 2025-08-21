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
        Schema::create('convocations', function (Blueprint $table) {
            $table->id();
            $table->string('titre'); // Sujet de la réunion
            $table->text('description')->nullable();
            $table->dateTime('date_reunion'); // Date et heure de la réunion
            $table->string('lieu'); // Lieu de la réunion
            $table->enum('statut', ['planifiée', 'reportée', 'annulée'])->default('planifiée');
            // Relation avec utilisateurs (si tu as une table users)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convocations');
    }
};
