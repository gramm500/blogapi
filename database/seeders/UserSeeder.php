<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->times(10)->create();
        $users = User::all();
        foreach ($users as $user) {
            $user->assignRole('user');
            $user->givePermissionTo('write');
        }
    }
}
