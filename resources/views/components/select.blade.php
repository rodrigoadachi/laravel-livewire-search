@props([
  'name',
  'options' => [],
  'model' => null,
  'placeholder' => 'Selecione',
  'disabled' => false
])

<div class="w-full">
  <select
    name="{{ $name }}"
    wire:model="{{ $model }}"
    class="text-zinc-200 w-full h-10 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed @error($name) border-red-500 @enderror"
    style="background-color: #18181b;"
    {{ $disabled ? 'disabled' : '' }}
  >
    <option value="">{{ $placeholder }}</option>
    @foreach ($options as $value => $label)
      <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
  </select>
  @error($name)
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
  @enderror
</div>
