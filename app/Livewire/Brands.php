<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Brand;
use App\Livewire\Traits\WithAlerts;
use Illuminate\Support\Facades\Request;

class Brands extends Component
{
  use WithPagination, WithAlerts;

  public $query = '';
  public $isEditing = false;

  public $perPage = 10;

  public $showModal = false;
  public $showConfirmDeleteModal = false;
  public $brandNameOnDelete = '';

  public $brandId, $name, $description;

  protected $paginationTheme = 'tailwind';

  public function mount()
  {
    $this->dispatch('show-loading');
  }

  public function dehydrate()
  {
    $this->dispatch('hide-loading');
  }

  public function render()
  {
    $brandsQuery = Brand::query();

    if (!empty($this->query)) {
      $brandsQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->query) . '%']);
    }

    $this->brands = $brandsQuery->orderBy('name', 'asc')->paginate($this->perPage);

    return view('livewire.brands', [
      'brands' => $brandsQuery->paginate($this->perPage),
      'perPage' => $this->perPage
    ]);
  }

  public function save()
  {
    try {
      $this->validate([
        'name' => 'required|unique:brands,name,' . ($this->brandId ?? 'NULL') . ',id',
        'description' => 'nullable|string'
      ], [
        'name.required' => 'O nome da marca é obrigatório.',
        'name.unique' => 'Já existe uma marca com este nome.',
        'description.required' => 'A descrição da marca é obrigatória.',
      ]);

      if ($this->brandId) {
        $brand = Brand::find($this->brandId);
        if (!$brand) {
          throw new \Exception('Marca não encontrada.');
        }
        $brand->update([
          'name' => $this->name,
          'description' => $this->description,
        ]);
        $this->addAlert('Marca atualizada com sucesso!', 'success');
      } else {
        Brand::create([
          'name' => $this->name,
          'description' => $this->description,
        ]);
        $this->addAlert('Marca criada com sucesso!', 'success');
      }

      $this->closeModal();
    } catch (\Exception $e) {
      $this->addAlert('Erro ao salvar a marca: ' . $e->getMessage(), 'error');
    }
  }

  public function updatingQuery()
  {
    $this->resetPage();
    $this->updateURL();
  }

  public function clearFilters()
  {
    $this->reset(['query', 'perPage']);
    $this->resetPage();
    $this->updateURL();
  }

  public function openModal($id = null)
  {
    $this->dispatch('show-loading');
    if ($id) {
      $brand = Brand::find($id);
      if ($brand) {
        $this->brandId = $brand->id;
        $this->name = $brand->name;
        $this->description = $brand->description;
        $this->isEditing = true;
      }
    } else {
      $this->reset(['brandId', 'name', 'description']);
      $this->isEditing = false;
    }
    $this->showModal = true;
    $this->dispatch('hide-loading');
  }

  public function closeModal()
  {
    $this->reset(['brandId', 'name', 'description', 'showModal', 'isEditing']);
  }

  public function closeConfirmDeleteModal()
  {
    $this->showConfirmDeleteModal = false;
  }

  public function resetFields()
  {
    $this->reset(['name', 'description', 'brandId', 'isEditing']);
  }

  public function updateURL()
  {
    $this->dispatch('update-url', [
      'query' => $this->query,
      'perPage' => $this->perPage,
    ]);
  }

}
