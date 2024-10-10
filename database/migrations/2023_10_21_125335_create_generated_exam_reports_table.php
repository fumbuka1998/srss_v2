<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneratedExamReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generated_exam_reports', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('academic_year_id')->constrained('academic_years');
            $table->foreignId('class_id')->constrained('school_classes');
            $table->foreignId('stream_id')->constrained('streams');
            $table->foreignId('generated_by')->constrained('users');
            $table->foreignId('exam_report_id')->constrained('exam_reports');
            $table->foreignId('term_id')->constrained('semesters');
            $table->json('exam_type_combination');
            $table->json('subject_type_combination');
            $table->foreignId('escalation_level_id')->constrained('escalation_levels');
            $table->boolean('is_published')->default(0);
            $table->boolean('include_signature')->default(0);
            $table->timestamps();
        });

        // $table->enum('position', ['yes','no'])->default('yes');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generated_exam_reports');
    }
}
