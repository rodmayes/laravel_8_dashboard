<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsPlaytomicTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.club_index',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.club_create',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.club_show',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.club_edit',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.club_delete',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.timetable_index',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.timetable_show',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.timetable_create',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.timetable_edit',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.timetable_delete',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.resource_index',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.resource_show',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.resource_create',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.resource_edit',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.resource_delete',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.booking_index',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.booking_show',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.booking_create',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.booking_edit',
            ],
            [
                'section' => 'Playtomic',
                'title' => 'playtomic.booking_delete',
            ],
        ];

        Permission::insert($permissions);
    }
}
