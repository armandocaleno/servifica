<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);                
        $this->call(CompanySeeder::class);
        $this->call(AccountClassSeeder::class);
        $this->call(AccountTypeSeeder::class);
        $this->call(AccountingSeeder::class);
        Partner::factory(30)->create();
        $this->call(AccountSeeder::class);        
        $this->call(BankSeeder::class);     
        $this->call(BankAccountSeeder::class);     
    }
}
