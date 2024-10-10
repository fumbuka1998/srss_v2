<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterAssessmentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_assessment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_exam_report_id')->constrained('generated_exam_reports');
            $table->foreignId('student_id')->constrained('students');
            $table->string('code');
            $table->string('grade');
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
        Schema::dropIfExists('character_assessment_reports');
    }
}
