<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('group_id')->constrained('grade_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->integer('education_level_id');
            $table->double('from');
            $table->double('to');
            $table->string('remarks')->nullable();
            $table->double('points')->nullable();
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
        Schema::dropIfExists('grades');
    }
}
