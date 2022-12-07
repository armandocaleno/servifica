<?php

use App\Models\Transaction;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->integer('number')->unique();
            $table->string('reference')->nullable();
            $table->decimal('total', 8, 2, true);
            $table->json('content')->nullable();
            $table->json('voucher')->nullable();
            $table->json('aditional_info')->nullable();
            $table->enum('type', [Transaction::INDIVIDUAL, Transaction::LOTES, Transaction::PAGO]);
            $table->unsignedBigInteger('company_id');            
            $table->unsignedBigInteger('partner_id');
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->foreign('company_id')->references('id')->on('companies');
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
        Schema::dropIfExists('transactions');
    }
};
