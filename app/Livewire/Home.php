<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Livewire\Traits\WithAlerts;
use Illuminate\Support\Facades\Request;

class Home extends Component
{
  use WithPagination, WithAlerts;

  public $query = '';
  public $selectedCategories = [];
  public $selectedBrands = [];
  public $selectedCategory = null;
  public $selectedBrand = null;
  public $showModal = false;
  public $isEditing = false;
  public $perPage = 10;
  public $sortField = 'name';
  public $sortDirection = 'asc';

  public $productId, $name, $price, $stock;

  protected $paginationTheme = 'tailwind';

  public function mount()
  {
    $this->dispatch('show-loading');
    $this->query = Request::query('query', '');
    $this->selectedCategories = array_filter(explode(',', Request::query('categories', '')));
    $this->selectedBrands = array_filter(explode(',', Request::query('brands', '')));
    $this->perPage = Request::query('perPage', 10);
    $this->page = Request::query('page', 1);
    $this->sortField = Request::query('sortField', 'name');
    $this->sortDirection = Request::query('sortDirection', 'asc');
  }

  public function dehydrate()
  {
    $this->dispatch('hide-loading');
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

  public function openModal($id = null)
  {
    $this->dispatch('show-loading');
    if ($id) {
      $product = Product::find($id);
      if ($product) {
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->selectedCategories = [$product->category_id];
        $this->selectedBrands = [$product->brand_id];
        $this->isEditing = true;
      }
    } else {
      $this->reset(['productId', 'name', 'selectedCategories', 'selectedBrands']);
      $this->isEditing = false;
    }
    $this->showModal = true;
    $this->dispatch('hide-loading');
  }

  public function openNewProductModal()
  {
    $this->dispatch('show-loading');
    $this->reset(['productId', 'name', 'selectedCategory', 'selectedBrand']);
    $this->showModal = true;
    $this->dispatch('hide-loading');
  }

  public function closeModal()
  {
    $this->reset(['productId', 'name', 'selectedCategory', 'selectedBrand', 'showModal', 'price', 'stock']);
  }

  public function save()
  {
    try {
      $this->validate([
        'name' => 'required|unique:products,name,' . ($this->productId ?? 'NULL') . ',id',
        'selectedCategory' => 'required',
        'selectedBrand' => 'required',
      ]);

      if ($this->productId) {
        $product = Product::find($this->productId);
        if ($product) {
          $product->update([
            'name' => $this->name,
            'category_id' => $this->selectedCategory,
            'brand_id' => $this->selectedBrand,
          ]);
          $this->addAlert('Produto atualizado com sucesso!', 'success');
        } else {
          $this->addAlert('Produto não encontrado.', 'error');
        }
      } else {
        Product::create([
          'name' => $this->name,
          'category_id' => $this->selectedCategory,
          'brand_id' => $this->selectedBrand,
        ]);
        $this->addAlert('Produto criado com sucesso!', 'success');
      }

      $this->closeModal();
    } catch (\Exception $e) {
      $this->addAlert('Erro ao salvar o produto: ' . $e->getMessage(), 'error');
    }
  }

  public function delete()
  {
    $product = Product::find($this->productId);
    if ($product) {
      $product->delete();
      $this->addAlert('Produto deletado com sucesso!', 'success');
    } else {
      $this->addAlert('Produto não encontrado.', 'error');
    }

    $this->closeModal();
  }

  public function render()
  {
    $productsQuery = Product::query();

    if (!empty($this->query)) {
      $productsQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->query) . '%']);
    }

    if (!empty($this->selectedCategories)) {
      $productsQuery->whereIn('category_id', $this->selectedCategories);
    }

    if (!empty($this->selectedBrands)) {
      $productsQuery->whereIn('brand_id', $this->selectedBrands);
    }

    if ($this->sortField === 'category.name') {
      $productsQuery->join('categories', 'products.category_id', '=', 'categories.id')
        ->orderBy('categories.name', $this->sortDirection);
    } elseif ($this->sortField === 'brand.name') {
      $productsQuery->join('brands', 'products.brand_id', '=', 'brands.id')
        ->orderBy('brands.name', $this->sortDirection);
    } else {
      $productsQuery->orderBy($this->sortField, $this->sortDirection);
    }


    return view('livewire.home', [
      'products' => $productsQuery->paginate($this->perPage),
      'categories' => Category::all(),
      'brands' => Brand::all(),
      'perPage' => $this->perPage,
      'sortField' => $this->sortField,
      'sortDirection' => $this->sortDirection,
    ]);
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

}
