<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('class_level')->constrained('school_classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('start_from');
            $table->dateTime('end_on');
            $table->dateTime('marking_from');
            $table->dateTime('marking_to');
            $table->enum('status',['Closed','Open','Upcoming']);
            $table->foreignId('grading')->constrained('grade_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade')->onUpdate('cascade');
            // $table->boolean('show_division');
            $table->integer('created_by');
            // $table->enum('avg_equation',['subjects_done','subjects_allocation']);
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
        Schema::dropIfExists('exam_schedules');
    }
}
