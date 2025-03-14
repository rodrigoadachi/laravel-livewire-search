<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ProductSearch;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ProductSearchTest extends TestCase
{
  use RefreshDatabase;

  #[Test]
  public function it_can_search_products_by_name()
  {
    $product1 = Product::factory()->create([
      'name' => 'Notebook Gamer',
    ]);
    $product2 = Product::factory()->create([
      'name' => 'Teclado Mecânico',
    ]);

    Livewire::test(ProductSearch::class)
      ->set('query', 'Notebook')
      ->assertSee('Notebook Gamer')
      ->assertDontSee('Teclado Mecânico');
  }

  #[Test]
  public function it_can_filter_by_category()
  {
    $category1 = Category::factory()->create([
      'name' => 'Eletrônicos',
    ]);
    $category2 = Category::factory()->create([
      'name' => 'Eletrodomésticos',
    ]);

    $product1 = Product::factory()->create([
      'name' => 'Fone de Ouvido',
      'category_id' => $category1->id,
    ]);
    $product2 = Product::factory()->create([
      'name' => 'Geladeira',
    ]);

    Livewire::test(ProductSearch::class)
      ->set('selectedCategory', [$category1->id])
      ->assertSee('Fone de Ouvido')
      ->assertDontSee('Geladeira');
  }

  #[Test]
  public function it_can_filter_by_brand()
  {
    $brand1 = Brand::factory()->create([
      'name' => 'Apple',
    ]);
    $brand2 = Brand::factory()->create([
      'name' => 'Samsung',
    ]);

    $product1 = Product::factory()->create(['brand_id' => $brand1->id, 'name' => 'iPhone 14']);
    $product2 = Product::factory()->create(['brand_id' => $brand2->id, 'name' => 'Galaxy S23']);

    Livewire::test(ProductSearch::class)
      ->set('selectedBrand', [$brand1->id])
      ->assertSee('iPhone 14')
      ->assertDontSee('Galaxy S23');
  }

  #[Test]
  public function it_can_filter_by_category_and_brand()
  {
    $category1 = Category::factory()->create([
      'name' => 'Eletrônicos',
    ]);
    $brand1 = Brand::factory()->create([
      'name' => 'Apple',
    ]);
    $brand2 = Brand::factory()->create([
      'name' => 'Samsung',
    ]);

    $product1 = Product::factory()->create([
      'category_id' => $category1->id,
      'brand_id' => $brand1->id,
      'name' => 'iPad',
    ]);
    $product2 = Product::factory()->create([
      'category_id' => $category1->id,
      'brand_id' => $brand2->id,
      'name' => 'Galaxy Tab',
    ]);

    Livewire::test(ProductSearch::class)
      ->set('selectedCategory', [$category1->id])
      ->set('selectedBrand', [$brand1->id])
      ->assertSee('iPad')
      ->assertDontSee('Galaxy Tab');
  }

  #[Test]
  public function it_can_clear_filters()
  {
    $product1 = Product::factory()->create([
      'name' => 'MacBook',
    ]);
    $product2 = Product::factory()->create([
      'name' => 'Dell XPS',
    ]);

    Livewire::test(ProductSearch::class)
      ->set('query', 'MacBook')
      ->set('selectedCategory', 1)
      ->set('selectedBrand', 2)
      ->call('clearFilters')
      ->assertDontSee('MacBook');
  }
}
