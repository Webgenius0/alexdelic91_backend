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
        Schema::create('service_provider_profiles', function (Blueprint $table) {
            $table->id(); // Primary key with auto-increment
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade'); 
            $table->string('business_name', 150);
            $table->foreignId('service_type_id')->unique()->nullable()->constrained('service_types')->onDelete('set null'); 
            $table->string('phone', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->foreignId('service_location_id')->unique()->constrained('service_locations')->onDelete('cascade'); 
            $table->text('description')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('division', 100)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->boolean('profile_completed')->default(false);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_provider_profiles');
    }
};
