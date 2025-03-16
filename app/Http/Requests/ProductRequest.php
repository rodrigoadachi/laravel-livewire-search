<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => 'required|unique:products,name,' . ($this->productId ?? 'NULL') . ',id',
      'description' => 'required',
      'selectedCategory' => 'required|exists:categories,id',
      'selectedBrand' => 'required|exists:brands,id',
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => 'O nome do produto é obrigatório.',
      'name.unique' => 'Já existe um produto com este nome.',
      'description.required' => 'A descrição do produto é obrigatória.',
      'selectedCategory.required' => 'A categoria é obrigatória.',
      'selectedBrand.required' => 'A marca é obrigatória.',
    ];
  }
}
