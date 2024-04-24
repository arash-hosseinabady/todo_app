<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class AclSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::truncate();

        $permissionNames = config('permission_names');

        $dateTime = Carbon::now()->toDateTimeString();
        $guardName = Guard::getDefaultName(static::class);

        $permissions = collect($permissionNames)->flatten()->map(function ($permission) use ($dateTime, $guardName) {
            return [
                'name' => $permission,
                'guard_name' => $guardName,
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ];
        });

        $permissions = $permissions->whereNotIn('name', Permission::select('name')->get()->pluck('name'));

        Permission::insert($permissions->toArray());
    }
}
