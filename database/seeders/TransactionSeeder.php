<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Partner;
use App\Models\Total;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaction::factory(10)->create()->each(function(Transaction $transaction){                                   
            
        });
    }
}
