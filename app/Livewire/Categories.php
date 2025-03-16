<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Livewire\Traits\WithAlerts;
use Illuminate\Support\Facades\Request;

class Categories extends Component
{
  use WithPagination, WithAlerts;

  public $query = '';
  public $isEditing = false;

  public $perPage = 10;

  public $showModal = false;
  public $showConfirmDeleteModal = false;
  public $categoryNameOnDelete = '';

  public $categoryId, $name, $description;

  protected $paginationTheme = 'tailwind';

  public function mount()
  {
    $this->dispatch('show-loading');
    $this->query = Request::query('query', '');
    $this->perPage = Request::query('perPage', 10);
    $this->page = Request::query('page', 1);
  }

  public function dehydrate()
  {
    $this->dispatch('hide-loading');
  }

  public function render()
  {
    $categoriesQuery = Category::query();

    if (!empty($this->query)) {
      $categoriesQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->query) . '%']);
    }

    $this->categories = $categoriesQuery->orderBy('name', 'asc')->paginate($this->perPage);

    return view('livewire.categories', [
      'categories' => $categoriesQuery->paginate($this->perPage),
      'perPage' => $this->perPage
    ]);
  }

  public function save()
  {
    try {
      $this->validate([
        'name' => 'required|unique:categories,name,' . ($this->categoryId ?? 'NULL') . ',id',
        'description' => 'nullable|string'
      ], [
        'name.required' => 'O nome da categoria é obrigatório.',
        'name.unique' => 'Já existe uma categoria com este nome.',
        'description.required' => 'A descrição da categoria é obrigatória.',
      ]);

      if ($this->categoryId) {
        $category = Category::find($this->categoryId);
        if (!$category) {
          throw new \Exception('Categoria não encontrada.');
        }
        $category->update([
          'name' => $this->name,
          'description' => $this->description,
        ]);
        $this->addAlert('Categoria atualizada com sucesso!', 'success');
      } else {
        Category::create([
          'name' => $this->name,
          'description' => $this->description,
        ]);
        $this->addAlert('Categoria criada com sucesso!', 'success');
      }

      $this->closeModal();
    } catch (\Exception $e) {
      $this->addAlert('Erro ao salvar a categoria: ' . $e->getMessage(), 'error');
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
      $category = Category::find($id);
      if ($category) {
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->isEditing = true;
      }
    } else {
      $this->reset(['categoryId', 'name', 'description']);
      $this->isEditing = false;
    }
    $this->showModal = true;
    $this->dispatch('hide-loading');
  }

  public function closeModal()
  {
    $this->reset(['categoryId', 'name', 'description', 'showModal', 'isEditing']);
  }

  public function confirmDeleteModal($id)
  {
    $this->categoryNameOnDelete = Category::find($id)->name;
    $this->showConfirmDeleteModal = true;
  }

  public function delete()
  {
    $category = Category::find($this->categoryId);
    if ($category) {
      $category->delete();
      $this->addAlert('Categoria deletada com sucesso!', 'success');
    } else {
      $this->addAlert('Categoria não encontrada.', 'error');
    }
    $this->closeModal();
    $this->closeConfirmDeleteModal();
  }

  public function closeConfirmDeleteModal()
  {
    $this->showConfirmDeleteModal = false;
  }

  public function resetFields()
  {
    $this->reset(['name', 'description', 'categoryId', 'isEditing']);
  }

  public function updateURL()
  {
    $this->dispatch('update-url', [
      'query' => $this->query,
      'perPage' => $this->perPage,
    ]);
  }

}
