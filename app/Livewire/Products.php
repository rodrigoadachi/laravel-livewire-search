<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class Products extends Component
{
    public $products, $name, $price, $productId;
    public $isEditing = false;

    public function mount()
    {
        $this->products = Product::all();
    }

    public function create()
    {
        try {
            $this->validate([
                'name' => 'required|unique:products,name,' . ($this->productId ?? 'NULL') . ',id',
                'price' => 'required|numeric|min:0',
            ]);

            if ($this->isEditing) {
                $product = Product::find($this->productId);
                if (!$product) {
                    throw new \Exception('Produto não encontrado.');
                }
                $product->update([
                    'name' => $this->name,
                    'price' => $this->price,
                ]);
                session()->flash('success', 'Produto atualizado com sucesso!');
            } else {
                Product::create([
                    'name' => $this->name,
                    'price' => $this->price,
                ]);
                session()->flash('success', 'Produto criado com sucesso!');
            }

            $this->resetFields();
            $this->products = Product::all();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar produto: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                throw new \Exception('Produto não encontrado.');
            }

            $this->productId = $product->id;
            $this->name = $product->name;
            $this->price = $product->price;
            $this->isEditing = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao carregar produto: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                throw new \Exception('Produto não encontrado.');
            }

            $product->delete();
            $this->products = Product::all();
            session()->flash('success', 'Produto deletado com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao deletar produto: ' . $e->getMessage());
        }
    }

    public function resetFields()
    {
        $this->reset(['name', 'price', 'productId', 'isEditing']);
    }

    public function render()
    {
        return view('livewire.products', [
            'fields' => ['name', 'price'],
            'items' => $this->products,
            'title' => 'Produtos',
            'isEditing' => $this->isEditing
        ]);
    }
}
