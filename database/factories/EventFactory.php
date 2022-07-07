<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->name();
        $slug = Str::slug($name);

        //Get date from next week ending in 1 month
        $startAt = $this->faker->dateTimeBetween('+1 week', '+1 month');
        $endAt = $this->faker->dateTimeBetween($startAt, $startAt->format('Y-m-d H:i:s').' +2 days');
        return [
            'id' => Str::uuid()->toString(),
            'name' => $name,
            'slug' => $slug,
            'createdAt' => now(),
            'updatedAt' => now(),
            'startAt' => $startAt,
            'endAt' => $endAt,
            'deleted_at' => null,
        ];
    }

   
}
