<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
  public function getProducts($query, $selectedCategories, $selectedBrands, $sortField, $sortDirection, $perPage)
  {
    $selectedCategories = is_array($selectedCategories) ? $selectedCategories : explode(',', $selectedCategories);
    $selectedBrands = is_array($selectedBrands) ? $selectedBrands : explode(',', $selectedBrands);

    $products = Product::query()
      ->when(!empty($query), fn($q) => $q->where('name', 'LIKE', "%$query%"))
      ->when(!empty($selectedCategories), fn($q) => $q->whereIn('category_id', $selectedCategories))
      ->when(!empty($selectedBrands), fn($q) => $q->whereIn('brand_id', $selectedBrands))

      ->when($sortField === 'category.name', function($q) use ($sortDirection) {
        $q->join('categories', 'products.category_id', '=', 'categories.id')
          ->orderBy('categories.name', $sortDirection);
      })
      ->when($sortField === 'brand.name', function($q) use ($sortDirection) {
        $q->join('brands', 'products.brand_id', '=', 'brands.id')
          ->orderBy('brands.name', $sortDirection);
      })
      ->when(!in_array($sortField, ['category.name', 'brand.name']), function($q) use ($sortField, $sortDirection) {
        $q->orderBy($sortField, $sortDirection);
      })
      ->paginate($perPage);

    return $products;
  }

}
