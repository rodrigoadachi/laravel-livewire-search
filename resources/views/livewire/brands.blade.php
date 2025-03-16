@php
  $config = [
    ['id' => 'name', 'label' => 'Nome', 'orderable' => true],
    ['id' =>  'description', 'label' => 'Descrição', 'orderable' => false],
    [
      'id' => 'action',
      'label' => 'Ações',
      'render' => function($brand) {
        return view('components.pages.action-buttons', [
          'item' => $brand
        ])->render();
      }
    ],
  ];
@endphp

<div class="list p-6 w-full flex flex-col gap-4">

  <x-pages.brands.header />

  <div class="mt-4 w-full">
    <x-table :data="$brands" :config="$config" itemCountText="Marcas cadastradas" sortField="name" sortDirection="asc" />
  </div>

  <x-modal :title="$isEditing ? 'Editar Marca' : 'Adicionar Marca'" width="50%" :show="$showModal">
    <form wire:submit="save" class="flex flex-col gap-4">

      <x-input name="name" placeholder="Nome da Marca" model="name"/>
      <x-input name="description" placeholder="Descrição da Marca" model="description"/>

      <div class="inline-flex justify-end  items-end mt-4 w-full gap-2 ">
        <x-button label="Cancelar" type="button" action="closeModal" loadingTarget="closeModal" variant="default"/>
        @if($isEditing)
          <x-button label="Deletar" type="button" click="$wire.confirmDeleteModal('{{ $brandId }}')" loadingTarget="delete" variant="danger"/>
        @endif
        <x-button label="Salvar" type="submit" loadingTarget="save" variant="primary"/>
      </div>

    </form>
  </x-modal>

  <x-modal :title="'Deletar Categoria'" width="50%" :show="$showConfirmDeleteModal">
    <div class="flex flex-col gap-4">
      <p>Tem certeza que deseja deletar a marca <strong>{{ $brandNameOnDelete }}</strong>?</p>
      <div class="inline-flex justify-end  items-end mt-4 w-full gap-2 ">
        <x-button label="Cancelar" type="button" action="closeConfirmDeleteModal" loadingTarget="closeConfirmDeleteModal" variant="default"/>
        <x-button label="Deletar" type="button" action="delete" loadingTarget="delete" variant="danger"/>
      </div>
    </div>
  </x-modal>

</div>
