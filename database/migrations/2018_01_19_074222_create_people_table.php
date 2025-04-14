<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->char('fname',50);
            $table->char('mname',50);
            $table->char('lname',50);
            $table->char('addr', 100);
            $table->char('father_name', 100);
            $table->char('gfather_name', 100);
            $table->char('citizenship_no', 30);
            $table->date('date_of_issue');

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
        Schema::dropIfExists('people');
    }
}
