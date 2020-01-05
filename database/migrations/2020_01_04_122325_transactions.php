<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('details');
            $table->string('receiver_account');
            $table->string('receiver_name');
            $table->decimal('amount')->comment('amount without any fees');
            $table->decimal('fee_percentage');
            $table->decimal('fee_amount');
            $table->decimal('total')->comment('amount + fee_amount');
            $table->string('currency');
            $table->string('status')->default('pending');
            $table->timestamps();

        /** 
         * From the task: No relation of user needed. We assume that system knows user. 
         * We calculate amounts by transactions assigned to some user_id.
         */
            /* 
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}