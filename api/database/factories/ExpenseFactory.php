<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'description' => fake()->name(),
            'amount' => $this->faker->randomFloat(2, 1, 191),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d')
        ];
    }
}
