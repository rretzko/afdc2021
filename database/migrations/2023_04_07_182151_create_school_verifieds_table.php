<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolVerifiedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_verifieds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eventversion_id')->constrained();
            $table->foreignId('school_id')->constrained();
            $table->boolean('verified')->default(0);
            $table->timestamps();
            $table->unique(['eventversion_id','school_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_verifieds');
    }
}
