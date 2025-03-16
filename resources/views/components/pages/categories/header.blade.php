<div class="flex flex-row gap-4 w-full rounded-md border border-gray-700 p-4 items-center">
  <x-input
    wire:model.debounce.500ms="query"
    name="name"
    placeholder="Buscar Categoria"
    model="query"
    live
  />

   <x-button
    label="Limpar Filtros"
    action="clearFilters"
    loadingTarget="clearFilters"
    variant="default"
    icon="o-trash"
  />

  <x-button
    label="Adicionar Categoria"
    action="openModal"
    loadingTarget="openModal"
    variant="primary"
    icon="o-plus"
  />
</div>
