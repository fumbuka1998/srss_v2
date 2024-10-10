<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentResultReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_result_reports', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('generated_exam_report_id')->constrained('generated_exam_reports');
            $table->string('full_name')->nullable();
            $table->foreignId('student_id')->constrained('students')->onDelete('restrict');
            $table->longText('meta_data');
            $table->float('avg')->nullable();
            $table->string('grade')->nullable();
            $table->string('division')->nullable();
            $table->integer('points')->nullable();
            $table->integer('class_position')->nullable();
            $table->integer('stream_position')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');


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
        Schema::dropIfExists('student_result_reports');
    }
}
