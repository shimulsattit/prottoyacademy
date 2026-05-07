<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);

        $permissions = Permission::pluck('id','id')->all();
        $user = Admin::find(1);
       
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
