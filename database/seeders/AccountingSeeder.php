<?php

namespace Database\Seeders;

use App\Models\AccountClass;
use App\Models\Accounting;
use App\Models\AccountType;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $class = AccountClass::all()->random();
        $type = AccountType::all()->random();
        $company = Company::find(1);

        Accounting::create([
            'code' => '1.10.0001',
            'name' => 'Accounting test',
            'account_class_id' => $class->id,
            'account_type_id' => $type->id,
            'company_id' => $company->id
        ]);
    }
}
