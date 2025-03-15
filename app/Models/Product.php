<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
  use HasFactory, HasUuids;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = ['name', 'description', 'price', 'stock', 'brand_id', 'category_id'];

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }

   public function getPriceAttribute($value)
  {
    if (!is_numeric($value)) {
      throw new \InvalidArgumentException('O preço deve ser um valor numérico.');
    }
    return $value / 100;
  }

  public function setPriceAttribute($value)
  {
    if (!is_numeric($value)) {
      throw new \InvalidArgumentException('O preço deve ser um valor numérico.');
    }
    if ($value < 0) {
      throw new \InvalidArgumentException('O preço não pode ser negativo.');
    }
    $this->attributes['price'] = (int) ($value * 100);
  }
}
