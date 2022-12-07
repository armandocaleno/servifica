<?php

namespace Database\Factories;

use App\Models\Accounting;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partner>
 */
class PartnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $code = $this->faker->unique()->bothify('######');
        $identity = $this->faker->unique()->bothify('#########');
        $company = Company::all()->random();

        return [
            'code' => $code,
            'name' => $this->faker->name,
            'lastname' => $this->faker->lastName(),
            'identity' => $identity,
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'company_id' => $company->id,
            'accounting_id' => Accounting::all()->random()
        ];
    }
}
