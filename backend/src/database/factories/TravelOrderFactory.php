<?php

namespace Database\Factories;

use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'solicitante' => $this->faker->name,
            'destino' => $this->faker->city,
            'data_ida' => $this->faker->date,
            'data_volta' => $this->faker->date,
            'status' => $this->faker->randomElement(['solicitado', 'aprovado', 'cancelado']),
        ];
    }
}
