@props([
  'name',
  'options' => [],
  'model' => null,
  'value' => null,
  'placeholder' => 'Selecione',
  'live' => false,
  'disabled' => false,
  'multiple' => false
])

<div
  class="relative w-full"
  x-data="selectDropdown(@entangle($model), {{ json_encode($options) }}, '{{ $placeholder }}', {{ $multiple ? 'true' : 'false' }}, '{{ $value }}')"
  @click.away="closeDropdown"
>
  {{-- Campo de seleção --}}
  <div
    @click="toggleDropdown"
    class="text-zinc-200 w-full h-10 p-2 border border-gray-300 rounded-md flex items-center justify-between cursor-pointer"
    style="background-color: #18181b;"
  >
    <span x-text="displayText()"></span>
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
  </div>

  {{-- Dropdown com opções --}}
  <div
    x-show="open"
    x-transition
    class="absolute z-10 mt-1 w-full bg-zinc-900 border border-gray-600 rounded-md shadow-lg max-h-48 overflow-auto"
  >
    <ul class="p-2">
      @foreach ($options as $value => $label)
        <li
          class="cursor-pointer p-2 hover:bg-zinc-700 flex items-center gap-2"
          @click="toggleSelection('{{ $value }}')"
        >
          @if ($multiple)
            <input
              type="checkbox"
              wire:model{{ $live ? '.live' : '' }}="{{ $model }}"
              value="{{ $value }}"
              x-bind:checked="isSelected('{{ $value }}')"
              class="w-4 h-4"
            >
          @else
            <input
              type="radio"
              wire:model{{ $live ? '.live' : '' }}="{{ $model }}"
              value="{{ $value }}"
              x-bind:checked="isSelected('{{ $value }}')"
              class="w-4 h-4"
            >
          @endif
          <span>{{ $label }}</span>
        </li>
      @endforeach
    </ul>
  </div>

  {{-- Campo hidden para Livewire --}}
  <input type="hidden" name="{{ $name }}" x-model="selected">

  @error($name)
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
  @enderror
</div>

{{-- Alpine.js Script --}}
<script>
  function selectDropdown(selected, options, placeholder, multiple, value) {
    return {
      open: false,
      selected: multiple
        ? (Array.isArray(selected) && selected.length ? selected : (value ? value.split(',') : []))
        : (selected || value || ''),
      toggleDropdown() {
        this.open = !this.open;
      },
      closeDropdown() {
        this.open = false;
      },
      toggleSelection(value) {
        if (multiple) {
          if (this.selected.includes(value)) {
            this.selected = this.selected.filter(item => item !== value);
          } else {
            this.selected.push(value);
          }
        } else {
          this.selected = value;
          this.closeDropdown();
        }
      },
      isSelected(value) {
        return multiple ? this.selected.includes(value) : this.selected == value;
      },
      displayText() {
        if (multiple) {
          return this.selected.length ? this.selected.map(id => options[id]).join(', ') : placeholder;
        }
        return options[this.selected] || placeholder;
      }
    }
  }
</script>
