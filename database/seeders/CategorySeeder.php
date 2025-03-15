<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $categories = [
      ['name' => 'Eletrônicos', 'description' => 'Produtos como celulares, TVs, notebooks e acessórios eletrônicos.'],
      ['name' => 'Automotivo', 'description' => 'Itens para carros, motos e acessórios automotivos.'],
      ['name' => 'Moda', 'description' => 'Roupas, calçados e acessórios para todas as idades.'],
      ['name' => 'Esportes', 'description' => 'Equipamentos e acessórios esportivos para diversas modalidades.'],
      ['name' => 'Casa e Cozinha', 'description' => 'Eletrodomésticos e utensílios para o lar.'],
      ['name' => 'Brinquedos', 'description' => 'Brinquedos educativos e de lazer para todas as idades.'],
      ['name' => 'Beleza e Saúde', 'description' => 'Produtos de cuidados pessoais, cosméticos e perfumes.'],
    ];

    foreach ($categories as &$category) {
      $category['id'] = Str::uuid()->toString();
      $category['created_at'] = Carbon::now();
    }

    Category::insert($categories);
  }
}
