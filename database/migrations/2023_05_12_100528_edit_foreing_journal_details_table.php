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
        Schema::table('journal_details', function (Blueprint $table) {
            $table->dropForeign(['journal_id']);
    
            $table->foreign('journal_id')
                ->references('id')
                ->on('journals')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal_details', function (Blueprint $table) {
            $table->dropForeign(['journal_id']);
    
            $table->foreign('journal_id')
                ->references('id')
                ->on('journals');
        });
    }
};
