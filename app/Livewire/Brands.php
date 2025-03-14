<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Brand;
use App\Livewire\Traits\WithAlerts;

class Brands extends Component
{
  use WithAlerts;

  public $brands, $name, $description, $brandId;
  public $isEditing = false;

    public function mount()
  {
    $this->dispatch('show-loading');
    $this->brands = Brand::all();
  }

  public function dehydrate()
  {
    $this->dispatch('hide-loading');
  }

  public function create()
  {
    try {
      $this->validate([
        'name' => [
          'required',
          'unique:brands,name,' . ($this->brandId ?? 'NULL') . ',id'
        ],
        'description' => 'nullable|string'
      ]);

      if ($this->isEditing) {
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

      $this->resetFields();
      $this->brands = Brand::all();
    } catch (\Exception $e) {
      $this->addAlert('Erro ao salvar marca: ' . $e->getMessage(), 'error');
    }
  }

  public function edit($id)
  {
    $brand = Brand::find($id);
    if (!$brand) {
      $this->addAlert('Marca não encontrada.', 'error');
      return;
    }

    $this->brandId = $brand->id;
    $this->name = $brand->name;
    $this->description = $brand->description;
    $this->isEditing = true;
  }

  public function delete($id)
  {
      try {
          $brand = Brand::find($id);
          if (!$brand) {
              throw new \Exception('Marca não encontrada.');
          }
          $brand->delete();
          $this->brands = Brand::all();
          $this->addAlert('Marca deletada com sucesso!', 'success');
      } catch (\Exception $e) {
          $this->addAlert('Erro ao deletar marca: ' . $e->getMessage(), 'error');
      }
  }

  public function resetFields()
  {
      $this->reset(['name', 'description', 'brandId', 'isEditing']);
  }

  public function render()
  {
      return view('livewire.brands', [
          'fields' => ['name', 'description'],
          'items' => $this->brands,
          'title' => 'Marcas',
          'isEditing' => $this->isEditing
      ]);
  }
}
