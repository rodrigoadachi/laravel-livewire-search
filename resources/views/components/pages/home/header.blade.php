@props(['categories', 'brands'])

<div class="flex flex-row gap-4 w-full rounded-md border border-gray-700 p-4 items-center">
  <x-input
    wire:model.debounce.500ms="query"
    name="name"
    placeholder="Buscar Produto"
    model="query"
  />

  <x-select
    name="category"
    model="selectedCategories"
    :options="$categories->pluck('name', 'id')"
    placeholder="Selecione uma Categoria"
    multiple
  />

  <x-select
    name="brand"
    model="selectedBrands"
    :options="$brands->pluck('name', 'id')"
    placeholder="Selecione uma Marca"
    multiple
  />

  <x-button
    label="Limpar Filtros"
    action="clearFilters"
    loadingTarget="clearFilters"
    variant="default"
  />

  <x-button
    label="Adicionar Produto"
    action="openModal"
    loadingTarget="openModal"
    variant="primary"
  />
</div>
