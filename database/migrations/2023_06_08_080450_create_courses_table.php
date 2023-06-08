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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            //who is posting
            $table->string('user_token', 50);
            $table->string('course_name', 200);
            $table->string('thumbnail', 150);
            $table->string('video', 150)->nullable(true);
            $table->text('description')->nullable(true);
            $table->mediumInteger('type_id');
            $table->float('price');
            $table->smallInteger('lesson_num')->nullable(true);
            $table->smallInteger('video_lenght')->nullable(true);
            $table->smallInteger('follow')->default(0);
            $table->float('score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
