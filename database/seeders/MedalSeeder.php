<?php

namespace Database\Seeders;

use App\Enums\MedalConditionType;
use App\Models\Medal;
use Illuminate\Database\Seeder;

class MedalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amount = MedalConditionType::AvoidedAmount->value;
        $avoidedCount = MedalConditionType::AvoidedCount->value;
        $itemCount = MedalConditionType::ItemCount->value;

        $medals = [
            [
                'code' => 'item_count_1',
                'name' => '初登録',
                'description' => '気になるものを初めて登録する',
                'condition_type' => $itemCount,
                'threshold' => 1,
                'icon_key' => 'item_count_1',
                'display_order' => 10,
            ],
            [
                'code' => 'item_count_5',
                'name' => '登録に慣れてきた',
                'description' => '気になるものを5件登録する',
                'condition_type' => $itemCount,
                'threshold' => 5,
                'icon_key' => 'item_count_5',
                'display_order' => 20,
            ],
            [
                'code' => 'item_count_10',
                'name' => 'どんどん登録しよう！',
                'description' => '気になるものを10件登録する',
                'condition_type' => $itemCount,
                'threshold' => 10,
                'icon_key' => 'item_count_10',
                'display_order' => 30,
            ],
            [
                'code' => 'avoided_count_1',
                'name' => '購入見送りビギナー',
                'description' => 'はじめて購入を見送る',
                'condition_type' => $avoidedCount,
                'threshold' => 1,
                'icon_key' => 'avoided_count_1',
                'display_order' => 40,
            ],
            [
                'code' => 'avoided_count_5',
                'name' => '慣れてきた購入見送り',
                'description' => '購入を5件見送る',
                'condition_type' => $avoidedCount,
                'threshold' => 5,
                'icon_key' => 'avoided_count_5',
                'display_order' => 50,
            ],
            [
                'code' => 'avoided_count_10',
                'name' => 'ついに２桁、購入見送り',
                'description' => '購入を10件見送る',
                'condition_type' => $avoidedCount,
                'threshold' => 10,
                'icon_key' => 'avoided_count_10',
                'display_order' => 60,
            ],
            [
                'code' => 'avoided_amount_1000',
                'name' => 'ちょっぴり見送り',
                'description' => '購入を見送った金額が1,000円を超える',
                'condition_type' => $amount,
                'threshold' => 1000,
                'icon_key' => 'avoided_amount_1000',
                'display_order' => 70,
            ],
            [
                'code' => 'avoided_amount_5000',
                'name' => 'なかなかの見送り',
                'description' => '購入を見送った金額が5,000円を超える',
                'condition_type' => $amount,
                'threshold' => 5000,
                'icon_key' => 'avoided_amount_5000',
                'display_order' => 80,
            ],
            [
                'code' => 'avoided_amount_10000',
                'name' => 'ついに大台',
                'description' => '購入を見送った金額が10,000円を超える',
                'condition_type' => $amount,
                'threshold' => 10000,
                'icon_key' => 'avoided_amount_10000',
                'display_order' => 90,
            ],
        ];

        Medal::upsert(
            $medals,
            uniqueBy: ['code'],
            update: [
                'name',
                'description',
                'condition_type',
                'threshold',
                'icon_key',
                'display_order',
            ]
        );
    }
}
