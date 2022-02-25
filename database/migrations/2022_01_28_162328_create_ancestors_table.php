<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAncestorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('ancestors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('othernames');
            $table->dateTime('dob')->nullable();
            $table->timestamp('dod')->nullable();
            $table->string('placeofBirth')->nullable();
            $table->string('finalResidence');
            $table->string('FamilyName');
            $table->string('hometown');
            $table->string('territories');
            $table->string('occupation');
            $table->longText('biography')->nullable();
            $table->string('causeofDeath');
            $table->string('links')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ancestors');
    }
}
