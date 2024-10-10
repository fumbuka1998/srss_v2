<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('alumnis', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('student_id');
        //     $table->date('academic_year');
        //     $table->enum('achievement', ['O-LEVEL CERTIFICATE', 'A-LEVEL CERTIFICATE']);
        //     $table->timestamps();
        // });

        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('student_id')->constrained('students');
            $table->integer('admission_no');
            $table->integer('class_id');
            $table->integer('stream_id');
            $table->date('academic_year');
            $table->integer('graduation_year');
            $table->enum('achievement', ['O-LEVEL CERTIFICATE', 'A-LEVEL CERTIFICATE']);
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
        Schema::dropIfExists('alumnis');
    }
}
