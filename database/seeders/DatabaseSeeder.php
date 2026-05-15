<?php

namespace Database\Seeders;

use App\Models\User;
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

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@warunggalih.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@warunggalih.test',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        $makanan = \App\Models\Category::create(['name' => 'Makanan', 'slug' => 'makanan']);
        $minuman = \App\Models\Category::create(['name' => 'Minuman', 'slug' => 'minuman']);
        $snack = \App\Models\Category::create(['name' => 'Snack', 'slug' => 'snack']);

        \App\Models\Product::create([
            'category_id' => $makanan->id,
            'name' => 'Nasi Goreng Spesial',
            'price' => 25000,
            'stock' => 100,
            'image' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&q=80&w=400',
        ]);

        \App\Models\Product::create([
            'category_id' => $minuman->id,
            'name' => 'Es Kopi Susu',
            'price' => 15000,
            'stock' => 50,
            'image' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?auto=format&fit=crop&q=80&w=400',
        ]);
        
        \App\Models\Product::create([
            'category_id' => $snack->id,
            'name' => 'Kentang Goreng',
            'price' => 12000,
            'stock' => 200,
            'image' => 'https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&q=80&w=400',
        ]);
    }
}
