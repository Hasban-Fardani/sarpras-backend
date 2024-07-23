<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example',
            'nip' => 10232131,
            'name' => 'admin 1'
        ]);
        
        User::factory()->create([
            'email' => 'rpl@example',
            'username' => 'unit',
            'nip' => 10232132,
            'name' => 'unit RPL'
        ]);

        User::factory()->create([
            'email' => 'pengawas@example',
            'username' => 'pengawas',
            'nip' => 10232133,
            'name' => 'Pak Hendro'
        ]);
    }
}
