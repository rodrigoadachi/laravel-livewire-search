<?php

namespace App\Livewire;

use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Livewire\Traits\WithAlerts;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;

class Products extends BaseCrudComponent
{
  use WithPagination, WithAlerts;

  protected ProductService $productService;

  protected $model = Product::class;

  public $selectedCategories = [];
  public $selectedBrands = [];
  public $selectedCategory = null;
  public $selectedBrand = null;

  public $showConfirmDeleteModal = false;
  public $productNameOnDelete = '';
  public $brands = [];
  public $categories = [];

  public $productId, $name, $description, $price, $stock, $brand, $category;

  protected $paginationTheme = 'tailwind';

  public function mount()
{
    $this->dispatch('show-loading');

    $this->query = request()->query('query', '');

    $this->selectedCategories = request()->filled('categories')
        ? explode(',', request()->query('categories'))
        : [];

    $this->selectedBrands = request()->filled('brands')
        ? explode(',', request()->query('brands'))
        : [];

    $this->categories = Category::all();
    $this->brands = Brand::all();

    $this->perPage = request()->query('perPage', 10);
    $this->sortField = request()->query('sortField', 'name');
    $this->sortDirection = request()->query('sortDirection', 'asc');
    $this->page = request()->query('page', 1);

    $this->dispatch('hide-loading');
}


  public function dehydrate()
  {
    $this->dispatch('hide-loading');
  }

  public function render()
  {
    $products = app(ProductService::class)->getProducts(
      $this->query,
      $this->selectedCategories,
      $this->selectedBrands,
      $this->sortField,
      $this->sortDirection,
      $this->perPage,
    );

    return view('livewire.home', [
      'products' => $products,
      'categories' => $this->categories,
      'brands' => $this->brands,
      'perPage' => $this->perPage,
      'sortField' => $this->sortField,
      'sortDirection' => $this->sortDirection,
      'selectedCategories' => $this->selectedCategories,
      'selectedBrands' => $this->selectedBrands,
    ]);
  }

  public function updatingQuery()
  {
    $this->resetPage();
    $this->updateURL();
  }

  public function updatingSelectedCategories($value)
  {
    $this->updatingQuery();
  }

  public function updatingSelectedBrands($value)
  {
    $this->updatingQuery();
  }

  public function clearFilters()
  {
    $this->reset(['query', 'selectedCategories', 'selectedBrands', 'perPage']);
    $this->updatingQuery();
  }

  public function confirmDeleteModal($id)
  {
    if ($id) {
      $this->productId = $id;
      $product = Product::find($id);
      if ($product) {
        $this->productNameOnDelete = $product->name;
      }
      $this->showConfirmDeleteModal = true;
    }
  }

  public function closeConfirmDeleteModal()
  {
    $this->productNameOnDelete = '';
    $this->showConfirmDeleteModal = false;
  }

  public function openNewProductModal()
  {
    $this->dispatch('show-loading');
    $this->reset(['productId', 'name', 'description', 'selectedCategory', 'selectedBrand']);
    $this->selectedCategory = null;
    $this->selectedBrand = null;
    $this->isEditing = false;
    $this->showModal = true;
    $this->dispatch('hide-loading');
  }

  public function sortBy($field)
  {
    $this->sortField = $field;
    $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
  }

  public function sortByCategory()
  {
    $this->sortField = 'category.name';
    $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
  }

  private function getMaxPage()
  {
    return Product::query()->count() / $this->perPage;
  }

  public function updateURL()
  {
    $this->dispatch('update-url', [
      'query' => $this->query,
      'categories' => implode(',', $this->selectedCategories),
      'brands' => implode(',', $this->selectedBrands),
      'perPage' => $this->perPage,
      'sortField' => $this->sortField,
      'sortDirection' => $this->sortDirection,
      'page' => $this->page,
    ]);
  }

  public function openModal($id = null)
  {
    if ($id) {
      $this->model = $this->findById($id);
      $this->name = $this->model->name;
      $this->description = $this->model->description;
      $this->selectedCategory = (string)$this->model->category_id;
      $this->selectedBrand = (string)$this->model->brand_id;
      $this->isEditing = true;
      $this->productId = $id;
    } else {
      $this->resetFields();
      $this->isEditing = false;
    }

    $this->dispatch('hide-loading');
    $this->showModal = true;
  }

  public function save()
  {
    try {
      $validated = [];
      try {
        $validated = $this->validate([
            'name' => 'required|unique:products,name,' . ($this->productId ?? 'NULL') . ',id',
            'description' => 'required',
            'selectedCategory' => 'required|uuid|exists:categories,id',
            'selectedBrand' => 'required|uuid|exists:brands,id',
        ], [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.unique' => 'Já existe um produto com este nome.',
            'description.required' => 'A descrição do produto é obrigatória.',
            'selectedCategory.required' => 'A categoria é obrigatória.',
            'selectedBrand.required' => 'A marca é obrigatória.',
        ]);

    } catch (\Exception $e) {
        $this->addAlert('Erro: ' . $e->getMessage(), 'error');

        return;
    }

    if ($this->productId) {

      $product = Product::find($this->productId);

      if (!$product) {
        $this->addAlert('Produto não encontrado!', 'error');
        return;
      }

      $product->update([
        'name' => $this->name,
        'description' => $this->description,
        'category_id' => $this->selectedCategory,
        'brand_id' => $this->selectedBrand,
      ]);

      $this->addAlert('Produto atualizado!', 'success');
      $this->closeModal();
      return;
    } else {
      Product::create([
        'name' => $this->name,
        'description' => $this->description,
        'category_id' => $this->selectedCategory,
        'brand_id' => $this->selectedBrand,
        'price' => 0,
        'stock' => 0,
      ]);
    }
    $this->addAlert($this->productId ? 'Produto atualizado!' : 'Produto criado!', 'success');
    $this->closeModal();
    } catch (\Exception $e) {
      $this->addAlert('Erro: ' . $e->getMessage(), 'error');
    }
  }

  public function resetFields()
  {
    $this->reset(['productId', 'name', 'description', 'selectedCategory', 'selectedBrand']);
  }

  public function updated($property)
  {
    if (in_array($property, ['query', 'selectedCategories', 'selectedBrands', 'perPage', 'sortField', 'sortDirection', 'page'])) {
      $this->updateURL();
    }
  }

  public function updatingPage($value)
  {
    $this->page = $value;
    $this->updateURL();
  }

}
