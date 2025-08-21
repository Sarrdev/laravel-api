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
        Schema::create('proces_verbals', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->longText('contenu')->nullable(); // contenu détaillé du PV
            $table->date('date_reunion')->index();   // index pour optimiser les recherches par date
            $table->string('auteur')->nullable();    // auteur du PV
            $table->string('fichier', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proces_verbals');
    }
};
