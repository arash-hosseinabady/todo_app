<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DefaultAdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!($user = User::where('username', 'superadmin')->first())) {
            $user = User::create([
                'username' => 'superadmin',
                'password' => Hash::make('superadmin'),
            ]);
        }

        $user->syncPermissions(Permission::get());
    }
}
