<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SteakMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada kategori Steak
        $category = Category::firstOrCreate(
            ['slug' => 'steak'],
            ['name' => 'Steak']
        );

        $steaks = [
            [
                'name' => 'Wagyu A5 Ribeye 200g',
                'description' => 'Potongan ribeye Wagyu A5 asli Jepang dengan marbling sempurna. Disajikan dengan truffle mashed potato dan saus mushroom.',
                'price' => 850000,
                'stock' => 15,
                'is_available' => true,
            ],
            [
                'name' => 'Tomahawk Steak 1Kg',
                'description' => 'Steak raksasa dengan tulang panjang. Cocok untuk sharing 2-3 orang. Dilengkapi 3 pilihan saus dan wedges.',
                'price' => 1250000,
                'stock' => 5,
                'is_available' => true,
            ],
            [
                'name' => 'Tenderloin Meltique 150g',
                'description' => 'Daging sapi meltique yang super empuk dan juicy. Disajikan dengan french fries dan saus blackpepper.',
                'price' => 125000,
                'stock' => 40,
                'is_available' => true,
            ],
            [
                'name' => 'Sirloin Australian Beef 200g',
                'description' => 'Sirloin khas Australia dengan lapisan lemak di pinggir yang gurih. Saus BBQ spesial.',
                'price' => 165000,
                'stock' => 25,
                'is_available' => true,
            ],
            [
                'name' => 'T-Bone Steak 300g',
                'description' => 'Kombinasi sirloin dan tenderloin dalam satu potongan dengan tulang berbentuk T. Extra garlic butter.',
                'price' => 220000,
                'stock' => 20,
                'is_available' => true,
            ],
            [
                'name' => 'Black Angus Striploin 200g',
                'description' => 'Striploin Black Angus berkualitas tinggi, dimasak medium rare untuk rasa maksimal.',
                'price' => 310000,
                'stock' => 15,
                'is_available' => true,
            ],
            [
                'name' => 'Chicken Steak Crispy',
                'description' => 'Daging paha ayam tanpa tulang dibalut tepung renyah, disiram saus brown.',
                'price' => 45000,
                'stock' => 50,
                'is_available' => true,
            ],
            [
                'name' => 'Grilled Salmon Steak',
                'description' => 'Steak ikan salmon segar Norwegia panggang dengan saus lemon butter dan asparagus.',
                'price' => 185000,
                'stock' => 10,
                'is_available' => true,
            ],
            [
                'name' => 'Wagyu Saikoro Steak 150g',
                'description' => 'Potongan daging sapi saikoro wagyu bentuk dadu yang langsung meleleh di mulut.',
                'price' => 150000,
                'stock' => 30,
                'is_available' => true,
            ],
            [
                'name' => 'Ribs BBQ (Iga Bakar)',
                'description' => 'Iga sapi panggang lambat (slow-cooked) yang lepas dari tulang, dengan baluran saus BBQ madu.',
                'price' => 195000,
                'stock' => 15,
                'is_available' => true,
            ],
        ];

        foreach ($steaks as $steak) {
            Product::create([
                'category_id' => $category->id,
                'name' => $steak['name'],
                'description' => $steak['description'],
                'price' => $steak['price'],
                'stock' => $steak['stock'],
                'is_available' => $steak['is_available'],
            ]);
        }
    }
}
