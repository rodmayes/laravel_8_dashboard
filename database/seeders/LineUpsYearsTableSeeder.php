<?php

namespace Database\Seeders;

use App\Models\LineUps\Year;
use Illuminate\Database\Seeder;

class LineUpsYearsTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
                ['id' => '2022', 'name' => '2022/2023', 'created_at' => now(), 'updated_at' => now()],
                ['id' => '2023', 'name' => '2023/2024', 'created_at' => now(), 'updated_at' => now()],
                ['id' => '2024', 'name' => '2024/2025', 'created_at' => now(), 'updated_at' => now()],
                ['id' => '2025', 'name' => '2025/2026', 'created_at' => now(), 'updated_at' => now()],
        ];

        Year::insert($data);
    }
}
