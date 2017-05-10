<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterestUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interestuser', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->integer('interest_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('interest_id')->references('id')->on('interests')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(array('user_id', 'interest_id'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('interestuser');
    }
}
