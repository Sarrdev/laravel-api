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
        Schema::create('note_services', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->longText('contenu')->nullable(); // contenu détaillé du ns
            $table->date('date_publication');   // index pour optimiser les recherches par date
            $table->string('auteur')->nullable();    // auteur du ns
            $table->string('fichier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('note_services');
    }
};
