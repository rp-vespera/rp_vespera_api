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
        Schema::table('wbs_i_conversation', function (Blueprint $table) {
            $table->string('lead_stage')->nullable()->default('Intake');
            $table->string('relationship_stage')->nullable()->default('Neutral ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wbs_i_conversation', function (Blueprint $table) {
            //
        });
    }
};
