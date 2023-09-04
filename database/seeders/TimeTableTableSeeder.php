<?php

namespace Database\Seeders;

use App\Models\Timetable;
use Illuminate\Database\Seeder;

class TimeTableTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
                ['name' => '09:00', 'playtomic_id' => 'T7%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '09:30', 'playtomic_id' => 'T7%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '10:00', 'playtomic_id' => 'T8%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '10:30', 'playtomic_id' => 'T8%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '11:00', 'playtomic_id' => 'T9%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '11:30', 'playtomic_id' => 'T9%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '12:00', 'playtomic_id' => 'T10%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '12:30', 'playtomic_id' => 'T10%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '13:00', 'playtomic_id' => 'T11%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '13:30', 'playtomic_id' => 'T11%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '14:00', 'playtomic_id' => 'T12%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '14:30', 'playtomic_id' => 'T12%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '15:00', 'playtomic_id' => 'T13%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '15:30', 'playtomic_id' => 'T13%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '16:00', 'playtomic_id' => 'T14%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '16:30', 'playtomic_id' => 'T14%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '17:00', 'playtomic_id' => 'T15%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '17:30', 'playtomic_id' => 'T15%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '18:00', 'playtomic_id' => 'T16%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '18:30', 'playtomic_id' => 'T16%3A30', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '19:00', 'playtomic_id' => 'T17%3A00', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '19:30', 'playtomic_id' => 'T17%3A30', 'created_at' => now(), 'updated_at' => now()]
        ];

        Timetable::insert($data);
    }
}
