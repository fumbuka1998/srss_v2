<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamScheduleClassStreamSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_schedule_class_stream_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('class_id')->constrained('school_classes');
            $table->foreignId('stream_id')->constrained('streams')->nullable();
            $table->foreignId('subject_id')->constrained('subjects')->nullable();
            $table->foreignId('exam_schedule_id')->constrained('exam_schedules');
            $table->enum('status',['x','ux'])->default('ux');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_schedule_class_stream_subjects');
    }
}
