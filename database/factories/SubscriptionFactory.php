<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'broadcaster_id' => env('TWITCH_BROADCASTER_ID'),
            'subscriber_id' => User::factory(),
            'gifter_id' => $this->faker->boolean(10) ? User::factory() : null,
            'tier' => $this->faker->randomElement(['1000', '2000', '3000']),
        ];
    }
}
