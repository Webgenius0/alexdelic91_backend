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
        Schema::create('servise_provider_work_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider_id')->constrained('service_provider_profiles')->onDelete('cascade');
            $table->foreignId('day_id')->nullable()->constrained('days')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servise_provider_work_days');
    }
};
