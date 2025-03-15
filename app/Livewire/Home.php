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
  public $showModal = false;
  public $isEditing = false;

  public $productId, $name;

  protected $paginationTheme = 'tailwind';

  public function mount()
  {
    $this->dispatch('show-loading');
    $this->query = Request::query('query', '');
    $this->selectedCategories = explode(',', Request::query('categories', ''));
    $this->selectedBrands = explode(',', Request::query('brands', ''));
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

  public function clearFilters()
  {
    $this->reset(['query', 'selectedCategories', 'selectedBrands']);
    $this->resetPage();
    $this->updateURL();
  }

  public function updateURL()
  {
    $this->dispatch('update-url', [
      'query' => $this->query,
      'categories' => implode(',', array_filter($this->selectedCategories)),
      'brands' => implode(',', array_filter($this->selectedBrands))
    ]);
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
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
      ]);

      if ($this->productId) {
        $product = Product::find($this->productId);
        if ($product) {
          $product->update([
            'name' => $this->name,
            'category_id' => $this->selectedCategory,
            'brand_id' => $this->selectedBrand,
            'price' => $this->price,
            'stock' => $this->stock,
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
          'price' => $this->price,
          'stock' => $this->stock,
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
    $products = Product::query();

    if (!empty($this->query)) {
      $products->where('name', 'like', '%' . $this->query . '%');
    }

    if (!empty($this->selectedCategories)) {
      $products->whereIn('category_id', array_filter($this->selectedCategories));
    }

    if (!empty($this->selectedBrands)) {
      $products->whereIn('brand_id', array_filter($this->selectedBrands));
    }

    return view('livewire.home', [
      'products' => $products->paginate(10),
      'categories' => Category::all(),
      'brands' => Brand::all()
    ]);
  }
}
