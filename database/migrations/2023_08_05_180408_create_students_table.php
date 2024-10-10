<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('admission_no')->unique();
            $table->string('uuid');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->binary('profile_pic');
            $table->string('lastname');
            $table->date('dob');
            $table->enum('gender',['male','female']);
            $table->integer('class_id');
            $table->integer('stream_id')->nullable();
            $table->string('nationality');
            $table->foreignId('religion_id')->constrained('religions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('house_id')->constrained('houses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('religion_sect_id')->constrained('religion_sects')->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('isDisabled')->default(0);
            $table->enum('admission_type',['continuing','started','transfered']);
            $table->date('registration_date');
            $table->integer('created_by');
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
        Schema::dropIfExists('students');
    }
}
