<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id' => User::factory(),
            'data' => $this->faker->date(),
            'name' => $this->faker->word,
            'valor' => $this->faker->randomFloat(2, 0, 1000),
            'tipo' => $this->faker->randomElement(['receita', 'gasto']),
            'saldo' => $this->faker->randomElement([$this->faker->randomFloat(2, 0, 1000), // Saldo positivo (receita)
        -$this->faker->randomFloat(2, 0, 1000), // Saldo negativo (gasto)
        ]),
        ];
        
    }
}
