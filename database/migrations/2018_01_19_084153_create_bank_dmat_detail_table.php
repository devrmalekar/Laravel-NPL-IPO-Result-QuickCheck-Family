<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDmatDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_dmat_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->char('bank_name', 100);
            $table->char('acc_num', 30);
            $table->char('dmat_id', 10);
            $table->char('client_id', 10);
            $table->integer('pid')->unsigned();
            $table->foreign('pid')->references('id')->on('people')->onDelete('cascade');
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
        Schema::dropIfExists('bank_dmat_detail');
    }
}
