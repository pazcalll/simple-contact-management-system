<?php

namespace Database\Factories;

use App\Models\LeadStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $leadStatusesCount = LeadStatus::count();
        return [
            //
            'name' => fake('id')->name(),
            'email' => fake('id')->email(),
            'mobile_number' => fake('id')->numberBetween(8000000000, 8999999999),
            'utm_source' => fake()->word(),
            'utm_medium' => fake()->word(),
            'utm_campaign' => fake()->word(),
            'is_private' => fake()->boolean(),
            'lead_status_id' => fake()->numberBetween(1, $leadStatusesCount)
        ];
    }
}
