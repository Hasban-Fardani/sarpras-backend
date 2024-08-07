<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Item;
use App\Models\IncomingItem;
use App\Models\IncomingItemDetail;
use App\Models\OutgoingItem;
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
        $this->call([
            EmployeeSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
