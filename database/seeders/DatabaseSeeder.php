<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Item;
use App\Models\ItemIn;
use App\Models\ItemInDetail;
use App\Models\ItemOut;
use App\Models\ItemOutDetail;
use App\Models\RequestItem;
use App\Models\RequestItemDetail;
use App\Models\SubmissionItem;
use App\Models\SubmissionItemDetail;
use App\Models\Supplier;
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
        Employee::factory()->create([
            'nip' => 111111111,
            'name' => 'Pak Toni',
            'position' => 'admin',
            'phone' => '081234567890',
            'email' => 'admin@example.com',
        ]);

        Employee::factory()->create([
            'nip' => 222222222,
            'name' => 'Pak Sutarsa',
            'position' => 'pengawas',
            'phone' => '081234567891',
            'email' => 'pengawas@example.com',
        ]);

        Employee::factory()->create([
            'nip' => 333333333,
            'name' => 'Bu Ani',
            'position' => 'unit kerja',
            'phone' => '081234567892',
            'email' => 'unit@example.com',
        ]);

        Employee::factory(10)->create();

        User::factory()->create([
            'nip' => 111111111,
            'username' => 'admin',
            'role' => 'admin'
        ]);
        
        User::factory()->create([
            'nip' => 222222222,
            'username' => 'pengawas',
            'role' => 'pengawas'
        ]);

        User::factory()->create([
            'nip' => 333333333,
            'username' => 'unit_rpl',
            'role' => 'unit'
        ]);

        Category::factory(10)->create();
        
        Item::factory(10)->create();
        
        Supplier::factory(10)->create();

        ItemIn::factory(10)->create();
        ItemInDetail::factory(30)->create();

        ItemOut::factory(10)->create();
        ItemOutDetail::factory(30)->create();

        SubmissionItem::factory(10)->create();
        SubmissionItemDetail::factory(30)->create();

        RequestItem::factory(10)->create();
        RequestItemDetail::factory(30)->create();
    }
}
