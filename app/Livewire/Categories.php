<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Livewire\Traits\WithAlerts;

class Categories extends Component
{
    use WithAlerts;

    public $categories, $name, $description, $categoryId;
    public $isEditing = false;

    public function mount()
    {
        $this->dispatch('show-loading');
        $this->categories = Category::all();
    }

    public function dehydrate()
    {
        $this->dispatch('hide-loading');
    }

    public function create()
    {
        try {
            $this->validate([
                'name' => 'required|unique:categories,name,' . $this->categoryId,
                'description' => 'nullable|string'
            ]);

            if ($this->isEditing) {
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

            $this->resetFields();
            $this->categories = Category::all();
        } catch (\Exception $e) {
            $this->addAlert('Erro ao salvar categoria: ' . $e->getMessage(), 'error');
        }
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if (!$category) {
            $this->addAlert('Categoria não encontrada.', 'error');
            return;
        }

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->isEditing = true;
    }

    public function update()
{
    try {
        $this->validate([
            'name' => 'required|unique:categories,name,' . $this->categoryId,
            'description' => 'nullable|string'
        ]);

        $category = Category::find($this->categoryId);
        if (!$category) {
            throw new \Exception('Categoria não encontrada.');
        }

        $category->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->addAlert('Categoria atualizada com sucesso!', 'success');
        $this->resetFields();
        $this->categories = Category::all();
    } catch (\Exception $e) {
        $this->addAlert('Erro ao atualizar categoria: ' . $e->getMessage(), 'error');
    }
}


    public function delete($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                throw new \Exception('Categoria não encontrada.');
            }
            $category->delete();
            $this->categories = Category::all();
            $this->addAlert('Categoria deletada com sucesso!', 'success');
        } catch (\Exception $e) {
            $this->addAlert('Erro ao deletar categoria: ' . $e->getMessage(), 'error');
        }
    }

    public function resetFields()
    {
        $this->reset(['name', 'description', 'categoryId', 'isEditing']);
    }

    public function render()
    {
        return view('livewire.categories', [
            'fields' => ['name', 'description'],
            'items' => $this->categories,
            'title' => 'Categorias',
            'isEditing' => $this->isEditing
        ]);
    }
}
