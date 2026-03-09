<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin Depot',
            'email' => 'admin@galon.com',
            'password' => Hash::make('password'),
            'phone' => '08123456789',
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Courier Galon',
            'email' => 'courier@galon.com',
            'password' => Hash::make('password'),
            'phone' => '08123456788',
            'role' => 'courier'
        ]);

        User::factory()->create([
            'name' => 'Customer',
            'email' => 'customer@galon.com',
            'password' => Hash::make('password'),
            'phone' => '08123456787',
            'role' => 'customer'
        ]);

        $this->call(ProductSeeder::class);
    }
}
