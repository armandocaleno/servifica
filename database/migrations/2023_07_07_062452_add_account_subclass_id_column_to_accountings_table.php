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
        Schema::table('accountings', function (Blueprint $table) {
            $table->unsignedBigInteger('account_subclass_id')->nullable()->after('level');

            $table->foreign('account_subclass_id')->references('id')->on('account_subclasses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accountings', function (Blueprint $table) {
            $table->dropForeign('accountings_account_subclass_id_foreign');
            $table->dropColumn('account_subclass_id');
        });
    }
};
