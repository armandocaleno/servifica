<?php

use App\Models\BankAccount;
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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->string('owner')->nullable();
            $table->enum('type', [BankAccount::AHORRO, BankAccount::CORRIENTE, BankAccount::CAJA])->default(BankAccount::AHORRO);
            $table->enum('status', [BankAccount::ACTIVO, BankAccount::INACTIVO])->default(BankAccount::ACTIVO);
            $table->string('bank')->nullable();
            $table->string('reference')->nullable();
            // $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('accounting_id');
            $table->unsignedBigInteger('company_id');
            // $table->foreign('bank_id')->references('id')->on('banks');
            $table->foreign('company_id')->references('id')->on('companies');
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
        Schema::dropIfExists('bank_accounts');
    }
};
