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

  public $productId, $name, $description, $price, $stock;

  protected $paginationTheme = 'tailwind';

  public function mount()
  {
    $this->dispatch('show-loading');

    $this->query = Request::query('query', '');
    $this->perPage = Request::query('perPage', 10);
    $this->page = Request::query('page', 1);
    $this->sortField = Request::query('sortField', 'name');
    $this->sortDirection = Request::query('sortDirection', 'asc');

    $this->selectedCategories = array_filter(explode(',', Request::query('categories', '')));
    $this->selectedBrands = array_filter(explode(',', Request::query('brands', '')));
  }

  public function dehydrate()
  {
    $this->dispatch('hide-loading');
  }

  public function render()
  {
    return view('livewire.home', [
      'products' => app(ProductService::class)->getProducts(
        $this->query,
        $this->selectedCategories,
        $this->selectedBrands,
        $this->sortField,
        $this->sortDirection,
        $this->perPage
      ),
      'categories' => Category::all(),
      'brands' => Brand::all(),
      'perPage' => $this->perPage,
      'sortField' => $this->sortField,
      'sortDirection' => $this->sortDirection,
    ]);
  }

  public function save()
  {
    try {
      $request = new ProductRequest();
      $request->merge(['productId' => $this->productId]);

      $validated = $this->validate(
        $request->rules(),
        $request->messages()
      );

      $data = [
        'name' => $validated['name'],
        'description' => $validated['description'],
        'category_id' => $validated['selectedCategory'],
        'brand_id' => $validated['selectedBrand'],
        'price' => 0,
        'stock' => 0,
      ];

      Product::updateOrCreate(
        ['id' => $this->productId],
        $data
      );

      $this->addAlert($this->productId ? 'Produto atualizado!' : 'Produto criado!', 'success');
      $this->closeModal();
    } catch (\Exception $e) {
      $this->addAlert('Erro: ' . $e->getMessage(), 'error');
    }
  }

  public function updatingQuery()
  {
    $this->resetPage();
    $this->updateURL();
  }

  public function updatingSelectedCategories()
  {
    $this->resetPage();
    $this->updateURL();
  }

  public function updatingSelectedBrands()
  {
    $this->resetPage();
    $this->updateURL();
  }

  public function updated($property)
  {
    if (in_array($property, ['query', 'selectedCategories', 'selectedBrands', 'perPage'])) {
      $this->resetPage();
      $this->updateURL();
    }
  }

  public function clearFilters()
  {
    $this->reset(['query', 'selectedCategories', 'selectedBrands', 'perPage']);
    $this->resetPage();
    $this->updateURL();
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
      'categories' => implode(',', array_filter($this->selectedCategories)),
      'brands' => implode(',', array_filter($this->selectedBrands)),
      'perPage' => $this->perPage,
      'sortField' => $this->sortField,
      'sortDirection' => $this->sortDirection,
    ]);
  }

  public function openModal($id = null)
  {
    if ($id) {
      $this->model = $this->findById($id);
      $this->name = $this->model->name;
      $this->description = $this->model->description;
      $this->selectedCategory = $this->model->category_id;
      $this->selectedBrand = $this->model->brand_id;
      $this->isEditing = true;
      $this->productId = $id;
    } else {
      $this->resetFields();
      $this->isEditing = false;
    }
    $this->dispatch('hide-loading');
    $this->showModal = true;
  }

  public function resetFields()
  {
    $this->reset(['productId', 'name', 'description', 'selectedCategory', 'selectedBrand']);
  }
}
