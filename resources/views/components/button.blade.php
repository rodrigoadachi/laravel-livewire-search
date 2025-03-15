@props([
  'label' => '',
  'action' => null,
  'click' => null,
  'loadingTarget' => null,
  'variant' => 'default',
  'type' => 'button',
  'icon' => null,
])

@php
  $styles = [
    'default' => 'bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md',
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md',
    'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-md',
    'danger' => 'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md',
  ];

  $selectedStyle = $styles[$variant] ?? $styles['default'];
@endphp

<button
  type="{{ $type }}"
  wire:click="{{ $action ? $action : '' }}"
  x-on:click="{{ $click ? $click : '' }}"
  wire:loading.attr="disabled"
  @if($loadingTarget)
    wire:target="{{ $loadingTarget }}"
  @endif
  class="{{ $selectedStyle }} flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed h-10 w-auto py-2 px-4 whitespace-nowrap relative"
>
  @if($icon)
    <x-dynamic-component :component="'heroicon-' . $icon" class="w-5 h-5" />
  @endif

  <span class="relative z-10">{{ $label }}</span>

  <div
    wire:loading wire:target="{{ $loadingTarget ?? $action }}"
    class="absolute inset-0 flex items-center justify-center bg-opacity-50 bg-white rounded-md"
  >
    <div class="animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-gray-800"></div>
  </div>
</button>
