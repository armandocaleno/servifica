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
        Schema::create('check_expense', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_id')->nullable()->constrained('checks')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_expense');
    }
};
