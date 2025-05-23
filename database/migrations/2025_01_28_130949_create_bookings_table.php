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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_provider_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('job_post_id')->nullable()->constrained('job_posts')->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('booking_date');
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'booked', 'completed', 'cancelled', 'deleted'])->default('booked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
