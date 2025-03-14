<div class="p-4 bg-zinc-900 shadow rounded-md">
  <h2 class="text-lg font-bold">{{ $title }}</h2>

  {{-- Formulário --}}
  <div class="flex gap-2 my-4">
    @foreach ($fields as $field)
      <input type="text" wire:model.defer="{{ $field }}" placeholder="{{ ucfirst($field) }}"
          class="border border-gray-300 p-2 rounded-md">
    @endforeach

    @if($isEditing)
      <x-button label="Update" action="update" loadingTarget="update" variant="primary"/>
      <x-button label="Cancel" action="resetFields" loadingTarget="resetFields" variant="secondary"/>
    @else
      <x-button label="Add" action="create" loadingTarget="create" variant="success"/>
    @endif
  </div>

  {{-- Listagem --}}
  <ul class="space-y-2">
    @foreach ($items as $item)
      <li class="flex justify-between items-center p-2 border rounded-md">
        <span>{{ implode(' - ', array_map(fn($field) => $item[$field] ?? '', $fields)) }}</span>
        <div class="flex gap-2">
          <x-button label="✏️ Edit" action="edit('{{ $item->id }}')" loadingTarget="edit" variant="warning"/>
          <x-button label="❌ Delete" action="delete('{{ $item->id }}')" loadingTarget="delete" variant="danger"/>
        </div>
      </li>
    @endforeach
  </ul>

  {{-- Mensagens de sucesso ou erro --}}
  @if(session()->has('success'))
    <div class="bg-green-500 text-white p-2 mt-4 rounded-md">
        {{ session('success') }}
    </div>
  @endif

  @if(session()->has('error'))
    <div class="bg-red-500 text-white p-2 mt-4 rounded-md">
        {{ session('error') }}
    </div>
  @endif
</div>
