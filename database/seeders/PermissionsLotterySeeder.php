<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionsLotterySeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'section' => 'Lottery',
                'title' => 'lottery.access',
            ],
            [
                'section' => 'Lottery',
                'title' => 'lottery.index',
            ],
            [
                'section' => 'Lottery',
                'title' => 'lottery.create',
            ],
            [
                'section' => 'Lottery',
                'title' => 'lottery.update',
            ],
            [
                'section' => 'Lottery',
                'title' => 'lottery.delete',
            ],
            [
                'section' => 'Lottery',
                'title' => 'lottery.results_index',
            ]
        ];

        $roleAdmin = Role::where('title', 'Admin')->first();
        $roleLottery = Role::firstOrCreate(['title' => 'Lottery']);

        foreach ($permissions as $permission) {
            $permissionToAssign = Permission::firstOrCreate($permission);
            $roleAdmin->permissions()->attach($permissionToAssign->id);
            $roleLottery->permissions()->attach($permissionToAssign->id);
        }
    }
}
