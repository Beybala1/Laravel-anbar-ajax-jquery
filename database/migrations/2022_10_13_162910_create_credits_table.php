<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('credit_client_id');
            $table->integer('credit_product_id');
            $table->float('depozit');
            $table->integer('credit_code')->microtime(true);
            $table->integer('time');
            $table->integer('percent');
            $table->float('credit_count');
            $table->integer('first_payment');
            $table->integer('user_id');
            $table->integer('credit_confirm');
            $table->integer('credit_cancel');
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
        Schema::dropIfExists('credits');
    }
}
