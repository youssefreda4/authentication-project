<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect(PermissionEnum::values())->map(function ($permission) {
            return ['name' => $permission];
        })->toArray();
        Permission::upsert($permissions,['name']);

        $ownerRole = Role::firstOrCreate(['name'=>'owner']);
        $ownerRole->permissions()->sync(Permission::pluck('id')->toArray());
    }
}
