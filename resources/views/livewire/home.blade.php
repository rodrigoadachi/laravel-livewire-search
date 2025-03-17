@php
  $config = [
    ['id' => 'name', 'label' => 'Nome', 'orderable' => true],
    ['id' => 'category.name', 'label' => 'Categoria', 'orderable' => true],
    ['id' => 'brand.name', 'label' => 'Marca', 'orderable' => true],
    ['id' =>  'description', 'label' => 'Descrição', 'orderable' => false],
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
    <x-table
      :data="$products"
      :config="$config"
      itemCountText="Produtos cadastrados"
      :sortField="$sortField"
      :sortDirection="$sortDirection"
      wire:key="table-{{ implode('-', $selectedCategories) }}-{{ implode('-', $selectedBrands) }}"
    />
  </div>

  <x-modal :title="$isEditing ? 'Editar Produto' : 'Adicionar Produto'" width="50%" :show="$showModal">
    <form wire:submit="save" class="flex flex-col gap-4">

      <x-input name="name" placeholder="Nome do Produto" model="name"/>
      <x-input name="description" placeholder="Descrição do Produto" model="description"/>

      <x-select
        name="category"
        model="selectedCategory"
        :value="$selectedCategory"
        :options="$categories->pluck('name', 'id')"
        placeholder="Selecione uma Categoria"
      />

      <x-select
        name="brand"
        model="selectedBrand"
        :value="$selectedBrand"
        :options="$brands->pluck('name', 'id')"
        placeholder="Selecione uma Marca"
      />

      <div class="inline-flex justify-end  items-end mt-4 w-full gap-2 ">
        <x-button
          label="Cancelar"
          type="button"
          action="closeModal"
          loadingTarget="closeModal"
          variant="default"
        />
        @if($isEditing)
          <x-button
            label="Deletar"
            type="button"
            click="$wire.confirmDeleteModal('{{ $productId }}')"
            loadingTarget="delete"
            variant="danger"
          />
        @endif
        <x-button
          label="Salvar"
          type="submit"
          loadingTarget="save"
          variant="primary"
        />
      </div>

    </form>
  </x-modal>

  <x-modal :title="'Deletar Produto'" width="50%" :show="$showConfirmDeleteModal">
    <div class="flex flex-col gap-4">
      <p>Tem certeza que deseja deletar o produto <strong>{{ $productNameOnDelete }}</strong>?</p>
      <div class="inline-flex justify-end  items-end mt-4 w-full gap-2 ">
        <x-button label="Cancelar" type="button" action="closeConfirmDeleteModal" loadingTarget="closeConfirmDeleteModal" variant="default"/>
        <x-button label="Deletar" type="button" click="$wire.delete('{{ $productId }}')" loadingTarget="delete" variant="danger"/>
      </div>
    </div>
  </x-modal>
</div>
