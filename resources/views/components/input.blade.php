@props([
  'type' => 'text',
  'name',
  'placeholder' => '',
  'model' => null,
  'disabled' => false
])

<div class="w-full">
  <input
    type="{{ $type }}"
    name="{{ $name }}"
    placeholder="{{ $placeholder }}"
    wire:model="{{ $model }}"
    class="text-zinc-200 w-full h-10 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500
      @error($name) border-red-500 @enderror"
    style="background-color: #18181b;"
  />
  @error($name)
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
  @enderror
</div>
