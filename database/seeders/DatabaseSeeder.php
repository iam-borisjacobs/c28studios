<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@c28studios.com'],
            [
                'name' => 'C28 Studio Admin',
                'password' => Hash::make(env('C28_ADMIN_PASSWORD', 'password')),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        $this->call(C28ContentSeeder::class);
    }
}
