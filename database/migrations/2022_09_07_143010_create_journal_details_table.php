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
        Schema::create('journal_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('debit_value', 8, 2, true);
            $table->decimal('credit_value', 8, 2, true);
            $table->unsignedBigInteger('journal_id');
            $table->unsignedBigInteger('accounting_id');
            $table->foreign('journal_id')->references('id')->on('journals');
            $table->foreign('accounting_id')->references('id')->on('accountings');
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
        Schema::dropIfExists('journal_details');
    }
};
