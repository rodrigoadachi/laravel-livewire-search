<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Carbon\Carbon;
class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $categories = Category::pluck('id', 'name')->toArray();
    $brands = Brand::pluck('id', 'name')->toArray();

    $products = [
      ['name' => 'iPhone 15 Pro', 'description' => 'Celular Apple topo de linha', 'category_name' => 'Eletrônicos', 'brand_name' => 'Apple', 'price' => 9999.99, 'stock' => 10],
      ['name' => 'Samsung Galaxy S23', 'description' => 'Smartphone Samsung avançado', 'category_name' => 'Eletrônicos', 'brand_name' => 'Samsung', 'price' => 7999.99, 'stock' => 15],
      ['name' => 'Nike Air Max', 'description' => 'Tênis esportivo Nike', 'category_name' => 'Moda', 'brand_name' => 'Nike', 'price' => 599.99, 'stock' => 50],
      ['name' => 'PlayStation 5', 'description' => 'Console de jogos da Sony', 'category_name' => 'Eletrônicos', 'brand_name' => 'Sony', 'price' => 4500.00, 'stock' => 5],
      ['name' => 'Smart TV LG 55” 4K', 'description' => 'Televisão LG com resolução 4K', 'category_name' => 'Eletrônicos', 'brand_name' => 'LG', 'price' => 3500.00, 'stock' => 8],
      ['name' => 'Fone Bose QuietComfort', 'description' => 'Fone de ouvido com cancelamento de ruído', 'category_name' => 'Eletrônicos', 'brand_name' => 'Bose', 'price' => 1200.00, 'stock' => 20],
      ['name' => 'Honda Civic 2024', 'description' => 'Carro Honda Civic 2024', 'category_name' => 'Automotivo', 'brand_name' => 'Honda', 'price' => 150000.00, 'stock' => 2],
      ['name' => 'Bola de Futebol Adidas', 'description' => 'Bola oficial da Adidas', 'category_name' => 'Esportes', 'brand_name' => 'Adidas', 'price' => 199.99, 'stock' => 30],
      ['name' => 'Máquina de Lavar Samsung', 'description' => 'Máquina de lavar roupas Samsung', 'category_name' => 'Casa e Cozinha', 'brand_name' => 'Samsung', 'price' => 2500.00, 'stock' => 5],
      ['name' => 'Perfume Chanel No. 5', 'description' => 'Perfume icônico da Chanel', 'category_name' => 'Beleza e Saúde', 'brand_name' => 'Chanel', 'price' => 799.99, 'stock' => 12],
    ];

    foreach ($products as &$product) {
      $product['id'] = Str::uuid()->toString();
      $product['created_at'] = Carbon::now();
      $product['category_id'] = $categories[$product['category_name']] ?? null;
      $product['brand_id'] = $product['brand_name'] ? ($brands[$product['brand_name']] ?? null) : null;
      $product['price'] = $product['price'] * 100;
      unset($product['category_name'], $product['brand_name']);
    }

    Product::insert($products);
  }
}
