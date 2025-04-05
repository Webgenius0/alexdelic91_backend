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
        Schema::table('wire_conversations', function (Blueprint $table) {
            $table->enum('chat_type', ['direct', 'job_post'])->default('direct')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wire_conversations', function (Blueprint $table) {
            $table->dropColumn('chat_type');
        });
    }
};
