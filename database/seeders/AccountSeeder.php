<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = [
            [
                'name' => 'Crédito mercadería por cobrar',
                'type' => Account::INGRESO
            ],
            [
                'name' => 'Cuota administrativa por cobrar',
                'type' => Account::INGRESO
            ],
            [
                'name' => 'Cuenta por cobrar',
                'type' => Account::INGRESO
            ],
            [
                'name' => 'Cuota extraordinaria por cobrar',
                'type' => Account::INGRESO
            ],
            [
                'name' => 'Cuota de ingreso por cobrar',
                'type' => Account::INGRESO
            ],
            [
                'name' => 'GPS - Equipo por cobrar',
                'type' => Account::INGRESO
            ],
            [
                'name' => 'Préstamos ordinarios',
                'type' => Account::INGRESO
            ],
            [
                'name' => 'Ahorros socios',
                'type' => Account::EGRESO
            ],
            [
                'name' => 'Ahorro para inversión',
                'type' => Account::EGRESO
            ],
            [
                'name' => 'Ahorro navideño',
                'type' => Account::EGRESO
            ],
            [
                'name' => 'Certificado de aportación',
                'type' => Account::EGRESO
            ],
        ];     
        
        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}
