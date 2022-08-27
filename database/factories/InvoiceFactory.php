<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\User;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'invoice_number' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'total' => $this->faker->randomFloat(2, 0, 999999.99),
            'status' => $this->faker->randomElement(["failed","successful","pending"]),
            'user_id' => User::factory(),
        ];
    }
}
