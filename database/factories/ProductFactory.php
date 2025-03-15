<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
  protected $model = Product::class;

  public function definition(): array
  {
    return [
      'id' => $this->faker->uuid(),
      'name' => $this->faker->unique()->word(),
      'description' => $this->faker->sentence(),
      'price' => $this->faker->randomFloat(2, 1, 100),
      'stock' => $this->faker->numberBetween(0, 100),
      'brand_id' => Brand::factory(),
      'category_id' => Category::factory(),
    ];
  }
}
