<?php

use App\Models\Partner;
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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('lastname');
            $table->string('identity')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('company_id');    
            $table->unsignedBigInteger('accounting_id');
            $table->enum('status', [Partner::ACTIVO, Partner::INACTIVO])->default(Partner::ACTIVO);          
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
        Schema::dropIfExists('partners');
    }
};
