<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Livewire\Traits\WithAlerts;

class Home extends Component
{
  use WithPagination, WithAlerts;

  public $query = '';
  public $selectedCategory = null;
  public $selectedBrand = null;
  public $showModal = false;
  public $isEditing = false;

  public $productId, $name;

  protected $paginationTheme = 'tailwind';

  public function mount()
  {
    $this->dispatch('show-loading');
  }

  public function dehydrate()
  {
    $this->dispatch('hide-loading');
  }

  public function updatingQuery()
  {
    $this->resetPage();
  }

  public function updatingSelectedCategory()
  {
    $this->resetPage();
  }

  public function updatingSelectedBrand()
  {
    $this->resetPage();
  }

  public function clearFilters()
  {
    $this->reset(['query', 'selectedCategory', 'selectedBrand']);
    $this->resetPage();
  }

  public function openModal($id = null)
  {
    $this->dispatch('show-loading');
    if ($id) {
      $product = Product::find($id);
      if ($product) {
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->selectedCategory = $product->category_id;
        $this->selectedBrand = $product->brand_id;
        $this->isEditing = true;
      }
    } else {
      $this->reset(['productId', 'name', 'selectedCategory', 'selectedBrand']);
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
    $this->reset(['productId', 'name', 'selectedCategory', 'selectedBrand', 'showModal']);
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
    $products = Product::query();

    if (!empty($this->query)) {
      $products->where('name', 'like', '%' . $this->query . '%');
    }

    if (!empty($this->selectedCategory)) {
    $products->where('category_id', strval($this->selectedCategory));
    }

    if (!empty($this->selectedBrand)) {
      $products->where('brand_id', strval($this->selectedBrand));
    }

    return view('livewire.home', [
      'products' => $products->paginate(10),
      'categories' => Category::all(),
      'brands' => Brand::all()
    ]);
  }
}
