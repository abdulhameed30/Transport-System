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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->integer('driver_id')->nullable();
            $table->dateTime('trip_date');
            $table->integer('city_id');
            $table->string('destination');
            $table->string('flight_number');
            $table->string('workers_count');
            $table->text('notes')->nullable();
            $table->enum('status', ['Available', 'In_Progress', 'Completed','Cancelled'])->default('Available');
            $table->string('odometer_image')->nullable();
            $table->dateTime('stage_1_time')->nullable();
            $table->dateTime('stage_2_time')->nullable();
            $table->dateTime('stage_3_time')->nullable();
            $table->dateTime('stage_4_time')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
