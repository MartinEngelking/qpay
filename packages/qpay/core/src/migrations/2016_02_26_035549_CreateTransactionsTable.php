<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * This table holds individual transactions.
 * For the purposes of this exercise it is assumed that all transactions belong to the same person and the same card.
 */
class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 12, 2);
            $table->string('merchant');
            $table->string('address');
            $table->string('zip');
            $table->dateTime('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}