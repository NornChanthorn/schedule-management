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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('f_name');
            $table->string('l_name');
            $table->string('student_id');
            $table->string('gender');
            $table->date('dob');
            $table->unsignedBigInteger('user_id');
            $table->foreignId('generation_id')->constrained('generations');
            $table->foreignId('group_id')->constrained('groups');
            $table->foreignId('major_id')->constrained('majors');
            $table->unique('user_id'); // Unique constraint on user_id
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
