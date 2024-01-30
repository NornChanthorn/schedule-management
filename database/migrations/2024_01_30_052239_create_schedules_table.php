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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->time('time_start');
            $table->time('time_end');
            $table->foreignId('day_id')->constrained('day_ofweeks');
            $table->foreignId('term_id')->constrained('terms');
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('group_id')->constrained('groups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
