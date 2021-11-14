<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount',18, 4);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('repayment_frequency');
            $table->decimal('interest_rate',18, 4);
            $table->decimal('arrangement_fee',18, 4);
            $table->decimal('amount_need_repayment',18,4);
            $table->decimal('amount_repayment',18,4);
            $table->bigInteger('current_month_repayment');
            $table->enum('status', ['LOAN_STATUS_NONE', 'LOAN_STATUS_IN_PROGRESS','LOAN_STATUS_OVERDUE','LOAN_STATUS_COMPLETED']);
            $table->boolean('deleted');
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
        Schema::dropIfExists('loans');
    }
}
