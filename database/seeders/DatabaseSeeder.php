<?php

namespace Database\Seeders;

use App\Models\Category;
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
        User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example',
            'nip' => 10232131,
            'name' => 'admin 1',
            'role' => 'admin'
        ]);
        
        User::factory()->create([
            'email' => 'rpl@example',
            'username' => 'unit',
            'nip' => 10232132,
            'name' => 'unit RPL',
            'role' => 'unit'
        ]);

        User::factory()->create([
            'email' => 'pengawas@example',
            'username' => 'pengawas',
            'nip' => 10232133,
            'name' => 'Pak Hendro',
            'role' => 'pengawas'
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
