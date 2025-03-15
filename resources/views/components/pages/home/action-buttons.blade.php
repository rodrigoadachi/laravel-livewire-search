@props(['item'])

<x-button
  label="Editar"
  x-data
  click="$dispatch('show-loading'); $wire.openModal('{{ $item->id }}')"
  wire:loading.attr="disabled"
  :loadingTarget="'openModal.' . $item->id"
  icon="o-pencil"
/>
