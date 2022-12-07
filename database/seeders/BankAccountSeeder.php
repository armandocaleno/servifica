<?php

namespace Database\Seeders;

use App\Models\Accounting;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank = Bank::all()->random();
        $accounting = Accounting::find(1);
        $company = Company::find(1);
        
        BankAccount::create([
            'number' => '22222222',
            'type' => rand(1,2),
            'owner' => 'Angel Guerrero',
            'bank_id' => $bank->id,
            'accounting_id' => $accounting->id,
            'company_id' => $company->id
        ]);
    }
}
