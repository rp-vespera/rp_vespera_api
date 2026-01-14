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
        Schema::create('wbs_i_chat_logs_information', function (Blueprint $table) {
            $table->id('conversation_log_history_id');
            $table->bigInteger('customer_psid');
            $table->string('chat_message');
            $table->dateTime('chat_date');
            $table->date('existing_leads');
            $table->date('existing_relationship');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
