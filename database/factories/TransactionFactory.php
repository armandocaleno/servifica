<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Partner;
use App\Models\Transaction;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $number = $this->faker->unique()->bothify('######');
        $total = $this->faker->randomElement([20.50, 29, 100, 520, 2500, 30.30]);
        $account = Account::all()->random();
        
        Cart::destroy();
        Cart::add([
            'id' => $account->id,
            'name' => $account->name,
            'qty' => 1,
            'price' => $total,
            'weight' => 100            
        ]);

        return [
            'date' => now(),
            'number' => $number,
            'reference' =>$this->faker->sentence(2),
            'total' => $total,
            'content' => Cart::content(),
            'partner_id' => Partner::all()->random(),
            'type' => Transaction::INDIVIDUAL
        ];
    }
}
