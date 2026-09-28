<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
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
            'user_id' => User::factory(),
            'name' => fake()->randomElement([
                'ワイヤレスイヤホン',
                'ゲーミングチェア',
                'ロボット掃除機',
                'モニターアーム',
                'キーボード',
                'マウス',
            ]),
            'price' => fake()->numberBetween(5000, 50000),
            'image_key' => 'default',
            'memo' => fake()->realText(50, 5),
            'status' => 'pending',
            'status_changed_at' => now(),
        ];
    }
}
