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
        Schema::create('accounting_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accounting_id')->unique()->nullable();
            $table->string('name');
            $table->string('description');
            $table->unsignedBigInteger('company_id');

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
        Schema::dropIfExists('accounting_configs');
    }
};
