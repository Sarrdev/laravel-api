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
        Schema::create('boite_idees', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet')->nullable(); // Nom optionnel
            $table->text('idee'); // Texte de l'idée
            $table->enum('statut', ['soumis', 'recu'])->default('soumis'); // Statut par défaut soumis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boite_idees');
    }
};
