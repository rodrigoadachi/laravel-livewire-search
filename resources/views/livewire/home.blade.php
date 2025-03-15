@php
  $config = [
    ['id' => 'name', 'label' => 'Nome'],
    ['id' => 'category.name', 'label' => 'Categoria'],
    ['id' => 'brand.name', 'label' => 'Marca'],
    ['id' =>  'description', 'label' => 'Descrição'],
    [
      'id' => 'action',
      'label' => 'Ações',
      'render' => function($product) {
        return view('components.pages.home.action-buttons', [
          'item' => $product
        ])->render();
      }
    ],
  ];
@endphp

<div class="list p-6 w-full flex flex-col gap-4">

  <x-pages.home.header :categories="$categories" :brands="$brands" />

  <div class="mt-4 w-full">
    <x-table :data="$products" :config="$config" itemCountText="Produtos cadastrados" />
  </div>

  <x-modal :title="$isEditing ? 'Editar Produto' : 'Adicionar Produto'" width="50%" :show="$showModal">
    <form wire:submit="save" class="flex flex-col gap-4">

      <x-input name="name" placeholder="Nome do Produto" model="name"/>

      <x-select
        name="category"
        model="selectedCategory"
        :options="$categories->pluck('name', 'id')"
        placeholder="Selecione uma Categoria"
      />

      <x-select
        name="brand"
        model="selectedBrand"
        :options="$brands->pluck('name', 'id')"
        placeholder="Selecione uma Marca"
      />

      <div class="inline-flex justify-end  items-end mt-4 w-full gap-2 ">
        <x-button label="Cancelar" type="button" action="closeModal" loadingTarget="closeModal" variant="danger"/>
        <x-button label="Salvar" type="submit" loadingTarget="save" variant="primary"/>
      </div>

    </form>
  </x-modal>

</div>
