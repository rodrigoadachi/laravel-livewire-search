@php
  $config = [
    ['id' => 'name', 'label' => 'Nome', 'orderable' => true],
    ['id' =>  'description', 'label' => 'Descrição', 'orderable' => false],
    [
      'id' => 'action',
      'label' => 'Ações',
      'render' => function($category) {
        return view('components.pages.action-buttons', [
          'item' => $category
        ])->render();
      }
    ],
  ];
@endphp

<div class="list p-6 w-full flex flex-col gap-4">

  <x-pages.categories.header />

  <div class="mt-4 w-full">
    <x-table :data="$categories" :config="$config" itemCountText="Categorias cadastradas" sortField="name" sortDirection="asc" />
  </div>

  <x-modal :title="$isEditing ? 'Editar Categoria' : 'Adicionar Categoria'" width="50%" :show="$showModal">
    <form wire:submit="save" class="flex flex-col gap-4">

      <x-input name="name" placeholder="Nome da Categoria" model="name"/>
      <x-input name="description" placeholder="Descrição da Categoria" model="description"/>

      <div class="inline-flex justify-end  items-end mt-4 w-full gap-2 ">
        <x-button label="Cancelar" type="button" action="closeModal" loadingTarget="closeModal" variant="default"/>
        @if($isEditing)
          <x-button label="Deletar" type="button" click="$wire.confirmDeleteModal('{{ $categoryId }}')" loadingTarget="delete" variant="danger"/>
        @endif
        <x-button label="Salvar" type="submit" loadingTarget="save" variant="primary"/>
      </div>

    </form>
  </x-modal>

  <x-modal :title="'Deletar Categoria'" width="50%" :show="$showConfirmDeleteModal">
    <div class="flex flex-col gap-4">
      <p>Tem certeza que deseja deletar a categoria <strong>{{ $categoryNameOnDelete }}</strong>?</p>
      <div class="inline-flex justify-end  items-end mt-4 w-full gap-2 ">
        <x-button label="Cancelar" type="button" action="closeConfirmDeleteModal" loadingTarget="closeConfirmDeleteModal" variant="default"/>
        <x-button label="Deletar" type="button" action="delete" loadingTarget="delete" variant="danger"/>
      </div>
    </div>
  </x-modal>

</div>
