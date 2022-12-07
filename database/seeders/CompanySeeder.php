<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'ruc' => '0999999999001',
                'businessname' => 'Empresa 1'
            ],
            [
                'ruc' => '0999999999002',
                'businessname' => 'Empresa 2'
            ]
        ];

        $user1 = User::find(1);
        $user2 = User::find(2);
        foreach ($companies as $company) {
            $newcompany = Company::create($company);

            $newcompany->users()->attach([$user1->id, $user2->id]);            
        }
    }
}
