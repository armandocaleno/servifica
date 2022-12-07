<?php

use App\Models\Account;
use App\Models\Accounting;
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
        Schema::create('accountings', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('group',[Accounting::SI, Accounting::NO])->default(Accounting::NO);
            $table->unsignedBigInteger('account_class_id');
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('company_id');       
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('account_class_id')->references('id')->on('account_classes');
            $table->foreign('account_type_id')->references('id')->on('account_types');
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
        Schema::dropIfExists('accountings');
    }
};
