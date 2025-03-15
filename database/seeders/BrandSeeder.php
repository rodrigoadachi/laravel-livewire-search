<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BrandSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $brands = [
      ['name' => 'Samsung', 'description' => 'Multinacional sul-coreana líder em eletrônicos.'],
      ['name' => 'Apple', 'description' => 'Empresa americana conhecida por seus iPhones e MacBooks.'],
      ['name' => 'Nike', 'description' => 'Marca global de artigos esportivos e vestuário.'],
      ['name' => 'Adidas', 'description' => 'Concorrente da Nike, focada em roupas e calçados esportivos.'],
      ['name' => 'Sony', 'description' => 'Famosa por PlayStation, TVs e câmeras digitais.'],
      ['name' => 'LG', 'description' => 'Fabricante de TVs, eletrodomésticos e celulares.'],
      ['name' => 'Bose', 'description' => 'Empresa especializada em áudio de alta qualidade.'],
      ['name' => 'Honda', 'description' => 'Fabricante japonesa de automóveis e motocicletas.'],
      ['name' => 'Ford', 'description' => 'Montadora americana conhecida por seus carros e caminhonetes.'],
      ['name' => 'Coca-Cola', 'description' => 'Maior fabricante de refrigerantes do mundo.'],
      ['name' => 'Chanel', 'description' => 'Marca francesa de luxo, especializada em moda e perfumes.'],
    ];

    foreach ($brands as &$brand) {
      $brand['id'] = Str::uuid()->toString();
      $brand['created_at'] = Carbon::now();
    }

    Brand::insert($brands);
  }
}
