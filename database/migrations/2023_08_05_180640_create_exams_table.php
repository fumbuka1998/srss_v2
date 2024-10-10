<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->boolean('is_dp')->default(0);
            $table->string('code')->nullable();
            $table->foreignId('grade_group')->constrained('grade_groups')->onUpdate('cascade')->onDelete('restrict');
            $table->double('total_marks');
            $table->double('passmark');
            $table->boolean('isCommutative')->default(0)->nullable();
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
        Schema::dropIfExists('exams');
    }
}
