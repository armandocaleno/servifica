<?php

namespace Database\Seeders;

use App\Models\AccountClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountClass::create([
            'name' => 'Activo'
        ]);

        AccountClass::create([
            'name' => 'Pasivo'
        ]);

        AccountClass::create([
            'name' => 'Patrimonio'
        ]);       
    }
}
