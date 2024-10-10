<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneratedExamReportIndrivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generated_exam_report_indrives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_exam_report_id')->constrained('generated_exam_reports')->onDelete('cascade');
            $table->string('uuid')->unique();
            $table->string('full_name');
            $table->string('admission_no')->nullable();
            // $table->string('score');
            // $table->string('marks_percentage')->nullable();
            // $table->string('grade');
            $table->string('remarks')->nullable();
            $table->string('class_teacher_comment')->nullable();
            $table->string('headmaster_comment')->nullable();
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
        Schema::dropIfExists('generated_exam_report_indrives');
    }
}
