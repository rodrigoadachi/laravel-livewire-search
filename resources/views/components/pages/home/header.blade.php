@props(['categories', 'brands'])

<div class="flex flex-row gap-4 w-full rounded-md border border-gray-700 p-4 items-center">
  <x-input
    wire:model.debounce.500ms="query"
    name="name"
    placeholder="Buscar Produto"
    model="query"
    live
  />

  <x-select
    name="categories"
    model="selectedCategories"
    :value="$selectedCategories ?? []"
    :options="$categories->pluck('name', 'id')"
    placeholder="Selecione Categorias"
    multiple
    live
  />

  <x-select
    name="brands"
    model="selectedBrands"
    :value="$selectedBrands ?? []"
    :options="$brands->pluck('name', 'id')"
    placeholder="Selecione Marcas"
    multiple
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
    label="Adicionar Produto"
    action="openModal"
    loadingTarget="openModal"
    variant="primary"
    icon="o-plus"
  />
</div>

