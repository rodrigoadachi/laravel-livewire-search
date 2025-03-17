@props([
  'name' => '',
  'options' => [],
  'model' => '',
  'value' => null,
  'placeholder' => 'Selecione',
  'multiple' => false,
  'live' => false,
])

<div
  class="relative w-full"
  x-data="multiSelectDropdown(
    @entangle($model).{{ $live ? 'live' : 'defer' }},
    {{ json_encode($options) }},
    '{{ $placeholder }}',
    '{{ $name }}',
    {{ $multiple ? 'true' : 'false' }},
    {{ $live ? 'true' : 'false' }},
    {{ json_encode($value) }}
  )"
  x-init="init()"
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
      @foreach ($options as $option => $label)
        <li
          class="cursor-pointer p-2 hover:bg-zinc-700 flex items-center gap-2"
          @click="toggleSelection('{{ $option }}')"
        >
          <input
            type="{{ $multiple ? 'checkbox' : 'radio' }}"
            wire:model{{ $live ? '.live' : '.defer' }}="{{ $model }}"
            value="{{ $option }}"
            x-bind:checked="isSelected('{{ $option }}')"
            class="w-4 h-4 cursor-pointer"
          />
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

<script>
  function multiSelectDropdown(selected, options, placeholder, paramName, multiple, live, value ) {
    return {
      open: false,
      selected: multiple ? (Array.isArray(selected) ? selected : []) : selected || '',
      init() {
        setTimeout(() => {
          const urlSelected = this.getSelectedFromUrl(paramName);

          if (multiple) {
            this.selected = Array.isArray(urlSelected) && urlSelected.length ? urlSelected : (Array.isArray(selected) ? selected : []);
          } else {
            this.selected = value || selected || '';
          }

          if (live) {
            this.$watch('selected', (value) => {
              this.$wire.set(paramName, value);
            });
          }
        }, 100);
      },
      toggleDropdown() {
        this.open = !this.open;
      },
      closeDropdown() {
        this.open = false;
      },
      toggleSelection(value) {
        if (multiple) {
          if (!Array.isArray(this.selected)) {
            this.selected = [];
          }

          if (this.selected.includes(value)) {
            this.selected = this.selected.filter(item => item !== value);
          } else {
            this.selected.push(value);
          }
        } else {
          this.selected = value;
          this.closeDropdown();
        }
        this.updateUrl(paramName);
      },
      isSelected(value) {
        return multiple ? this.selected.length && this.selected.includes(value) : this.selected == value;
      },
      displayText() {
        if (multiple) {
          return this.selected.length > 0
            ? (this.selected.length > 3
              ? `${this.selected.length} selecionados`
              : this.selected.map(id => options[id]).join(', '))
            : placeholder;
        }
        return options[this.selected] || placeholder;
      },
      getSelectedFromUrl(paramName) {
        const params = new URLSearchParams(window.location.search);
        const values = params.get(paramName);
        return values ? (multiple ? values.split(',') : [values]) : (multiple ? [] : []);
      },
      updateUrl(paramName) {
        const params = new URLSearchParams(window.location.search);
        if (multiple) {
          if (this.selected.length) {
            params.set(paramName, this.selected.join(','));
          } else {
            params.delete(paramName);
          }
        } else {
          params.set(paramName, this.selected);
        }
        window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
      }
    };
  }
</script>

