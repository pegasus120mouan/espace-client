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
        Schema::table('commandes', function (Blueprint $table) {
            $table->boolean('paiement_effectue')->default(false)->after('date_validation_point');
            $table->string('operateur_paiement')->nullable()->after('paiement_effectue');
            $table->timestamp('date_paiement')->nullable()->after('operateur_paiement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['paiement_effectue', 'operateur_paiement', 'date_paiement']);
        });
    }
};
