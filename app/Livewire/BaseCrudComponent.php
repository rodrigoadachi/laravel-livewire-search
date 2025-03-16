<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

abstract class BaseCrudComponent extends Component
{
  use WithPagination;

  protected $model;

  public $query = '';
  public $perPage = 10;
  public $showModal = false;
  public $isEditing = false;
  public $primaryKey = 'id';
  public $sortField = 'name';
  public $sortDirection = 'asc';

  public function closeModal()
  {
    $this->resetFields();
    $this->reset(['showModal']);
  }

  public function delete($id)
  {
    $item = $this->findById($id);
    if ($item) {
      $item->delete();
      session()->flash('success', 'Registro excluído com sucesso.');
      $this->closeModal();
      $this->closeConfirmDeleteModal();
    }
  }

  protected function findById($id)
  {
    return $this->model::findOrFail($id);
  }

  abstract public function render();
}
