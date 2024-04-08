<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'patients', function (Blueprint $table) {
                $table->id();
                $table->integer('identification');
                $table->string('full_name');
                $table->integer('age');
                $table->date('dob');
                $table->string('phone');
                $table->string('address');
                $table->string('neighborhood');
                $table->string('city');
                $table->string('email');
                $table->unsignedBigInteger('program_id')->nullable();
                $table->integer('cuatrimestre')->nullable();
                $table->string('antecedents')->nullable();
                $table->string('comments')->nullable();
                $table->timestamps();

                $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
};