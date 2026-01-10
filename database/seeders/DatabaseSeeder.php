<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductRecipe;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Users
        $pemilik = User::create([
            'name' => 'Pak Wayan',
            'email' => 'pemilik@nasijinggo.com',
            'password' => Hash::make('password'),
            'role' => 'pemilik',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $staffDapur = User::create([
            'name' => 'Made Sari',
            'email' => 'dapur@nasijinggo.com',
            'password' => Hash::make('password'),
            'role' => 'staff_dapur',
            'phone' => '081234567891',
            'is_active' => true,
            'created_by' => $pemilik->id,
        ]);

        $kasir = User::create([
            'name' => 'Ketut Rani',
            'email' => 'kasir@nasijinggo.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'phone' => '081234567892',
            'is_active' => true,
            'created_by' => $pemilik->id,
        ]);

        // Create Categories
        $catBeras = Category::create(['name' => 'Beras & Karbohidrat', 'description' => 'Bahan dasar makanan']);
        $catProtein = Category::create(['name' => 'Protein', 'description' => 'Lauk pauk']);
        $catSayur = Category::create(['name' => 'Sayur-sayuran', 'description' => 'Sayuran segar']);
        $catBumbu = Category::create(['name' => 'Bumbu & Rempah', 'description' => 'Bumbu dapur']);
        $catMinyak = Category::create(['name' => 'Minyak & Lemak', 'description' => 'Minyak goreng']);

        // Create Ingredients
        $beras = Ingredient::create([
            'category_id' => $catBeras->id,
            'name' => 'Beras',
            'unit' => 'kg',
            'stock_quantity' => 50,
            'minimum_stock' => 10,
            'price_per_unit' => 12000,
            'supplier_name' => 'Toko Sembako Jaya',
        ]);

        $ayam = Ingredient::create([
            'category_id' => $catProtein->id,
            'name' => 'Ayam Kampung',
            'unit' => 'kg',
            'stock_quantity' => 15,
            'minimum_stock' => 5,
            'price_per_unit' => 45000,
            'supplier_name' => 'Pasar Tradisional',
        ]);

        $telor = Ingredient::create([
            'category_id' => $catProtein->id,
            'name' => 'Telur Ayam',
            'unit' => 'kg',
            'stock_quantity' => 20,
            'minimum_stock' => 5,
            'price_per_unit' => 28000,
            'supplier_name' => 'Pasar Tradisional',
        ]);

        $tempe = Ingredient::create([
            'category_id' => $catProtein->id,
            'name' => 'Tempe',
            'unit' => 'kg',
            'stock_quantity' => 8,
            'minimum_stock' => 3,
            'price_per_unit' => 15000,
            'supplier_name' => 'Pengrajin Tempe Lokal',
        ]);

        $kangkung = Ingredient::create([
            'category_id' => $catSayur->id,
            'name' => 'Kangkung',
            'unit' => 'kg',
            'stock_quantity' => 5,
            'minimum_stock' => 2,
            'price_per_unit' => 8000,
            'supplier_name' => 'Pasar Sayur',
        ]);

        $cabai = Ingredient::create([
            'category_id' => $catBumbu->id,
            'name' => 'Cabai Rawit',
            'unit' => 'kg',
            'stock_quantity' => 3,
            'minimum_stock' => 1,
            'price_per_unit' => 50000,
            'supplier_name' => 'Pasar Tradisional',
        ]);

        $bawangMerah = Ingredient::create([
            'category_id' => $catBumbu->id,
            'name' => 'Bawang Merah',
            'unit' => 'kg',
            'stock_quantity' => 4,
            'minimum_stock' => 1,
            'price_per_unit' => 35000,
            'supplier_name' => 'Pasar Tradisional',
        ]);

        $bawangPutih = Ingredient::create([
            'category_id' => $catBumbu->id,
            'name' => 'Bawang Putih',
            'unit' => 'kg',
            'stock_quantity' => 3,
            'minimum_stock' => 1,
            'price_per_unit' => 40000,
            'supplier_name' => 'Pasar Tradisional',
        ]);

        $minyak = Ingredient::create([
            'category_id' => $catMinyak->id,
            'name' => 'Minyak Goreng',
            'unit' => 'liter',
            'stock_quantity' => 10,
            'minimum_stock' => 3,
            'price_per_unit' => 15000,
            'supplier_name' => 'Toko Sembako Jaya',
        ]);

        $santan = Ingredient::create([
            'category_id' => $catMinyak->id,
            'name' => 'Santan Kelapa',
            'unit' => 'liter',
            'stock_quantity' => 5,
            'minimum_stock' => 2,
            'price_per_unit' => 8000,
            'supplier_name' => 'Pengrajin Santan',
        ]);

        // Create Products
        $nasiJinggoOriginal = Product::create([
            'name' => 'Nasi Jinggo Original',
            'description' => 'Nasi Jinggo dengan lauk ayam suwir, tempe, telur pindang, dan sambal',
            'price' => 5000,
            'stock_quantity' => 100,
            'is_active' => true,
        ]);

        $nasiJinggoPedas = Product::create([
            'name' => 'Nasi Jinggo Pedas',
            'description' => 'Nasi Jinggo dengan tambahan sambal extra pedas',
            'price' => 6000,
            'stock_quantity' => 80,
            'is_active' => true,
        ]);

        $nasiJinggoSpesial = Product::create([
            'name' => 'Nasi Jinggo Spesial',
            'description' => 'Nasi Jinggo dengan porsi lauk lebih banyak',
            'price' => 8000,
            'stock_quantity' => 50,
            'is_active' => true,
        ]);

        // Create Product Recipes (Resep per porsi)
        // Nasi Jinggo Original
        ProductRecipe::create([
            'product_id' => $nasiJinggoOriginal->id,
            'ingredient_id' => $beras->id,
            'quantity_needed' => 0.1, // 100 gram per porsi
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoOriginal->id,
            'ingredient_id' => $ayam->id,
            'quantity_needed' => 0.03, // 30 gram
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoOriginal->id,
            'ingredient_id' => $telor->id,
            'quantity_needed' => 0.05, // 50 gram (sekitar 1 butir)
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoOriginal->id,
            'ingredient_id' => $tempe->id,
            'quantity_needed' => 0.02, // 20 gram
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoOriginal->id,
            'ingredient_id' => $cabai->id,
            'quantity_needed' => 0.01, // 10 gram
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoOriginal->id,
            'ingredient_id' => $bawangMerah->id,
            'quantity_needed' => 0.005, // 5 gram
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoOriginal->id,
            'ingredient_id' => $minyak->id,
            'quantity_needed' => 0.02, // 20 ml
        ]);

        // Nasi Jinggo Pedas (sama dengan original + cabai extra)
        ProductRecipe::create([
            'product_id' => $nasiJinggoPedas->id,
            'ingredient_id' => $beras->id,
            'quantity_needed' => 0.1,
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoPedas->id,
            'ingredient_id' => $ayam->id,
            'quantity_needed' => 0.03,
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoPedas->id,
            'ingredient_id' => $telor->id,
            'quantity_needed' => 0.05,
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoPedas->id,
            'ingredient_id' => $tempe->id,
            'quantity_needed' => 0.02,
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoPedas->id,
            'ingredient_id' => $cabai->id,
            'quantity_needed' => 0.02, // Double cabai
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoPedas->id,
            'ingredient_id' => $bawangMerah->id,
            'quantity_needed' => 0.005,
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoPedas->id,
            'ingredient_id' => $minyak->id,
            'quantity_needed' => 0.02,
        ]);

        // Nasi Jinggo Spesial (porsi lebih banyak)
        ProductRecipe::create([
            'product_id' => $nasiJinggoSpesial->id,
            'ingredient_id' => $beras->id,
            'quantity_needed' => 0.15, // 150 gram
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoSpesial->id,
            'ingredient_id' => $ayam->id,
            'quantity_needed' => 0.05, // 50 gram
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoSpesial->id,
            'ingredient_id' => $telor->id,
            'quantity_needed' => 0.05,
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoSpesial->id,
            'ingredient_id' => $tempe->id,
            'quantity_needed' => 0.03, // 30 gram
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoSpesial->id,
            'ingredient_id' => $cabai->id,
            'quantity_needed' => 0.015,
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoSpesial->id,
            'ingredient_id' => $bawangMerah->id,
            'quantity_needed' => 0.008,
        ]);

        ProductRecipe::create([
            'product_id' => $nasiJinggoSpesial->id,
            'ingredient_id' => $minyak->id,
            'quantity_needed' => 0.03,
        ]);

        echo "✅ Seeder completed successfully!\n";
        echo "📧 Login credentials:\n";
        echo "   Pemilik: pemilik@nasijinggo.com / password\n";
        echo "   Staff Dapur: dapur@nasijinggo.com / password\n";
        echo "   Kasir: kasir@nasijinggo.com / password\n";
    }
}