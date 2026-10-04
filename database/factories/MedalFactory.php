<?php

namespace Database\Factories;

use App\Enums\MedalConditionType;
use App\Models\Medal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medal>
 */
class MedalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->slug(2),
            'name' => 'テストメダル',
            'description' => 'テストメダルはテスト用のメダルです。',
            'condition_type' => MedalConditionType::AvoidedCount,
            'threshold' => 1,
            'icon_key' => 'avoided_count_1',
            'display_order' => fake()->unique()->numberBetween(1, 100),
        ];
    }
}
