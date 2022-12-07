<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::create([
            'name' => 'Banco Guayaquil'
        ]);

        Bank::create([
            'name' => 'Banco del Pichincha'
        ]);

        Bank::create([
            'name' => 'Banco Bolivariano'
        ]);

        Bank::create([
            'name' => 'Produbanco'
        ]);

        Bank::create([
            'name' => 'Banco del Pacífico'
        ]);
    }
}
