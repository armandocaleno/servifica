<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('total');
            $table->string('number')->nullable();
            $table->string('emisor')->nullable();
            $table->string('beneficiary')->nullable();
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('bank_account_id');

            $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');

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
        Schema::dropIfExists('payments');
    }
};
