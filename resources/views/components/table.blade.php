@props(['data', 'config', 'itemCountText' => 'Total de itens cadastrados'])

<table class="min-w-full border border-gray-700 rounded-md w-full">
  <thead class="bg-zinc-800 text-white">
    <tr>
      @foreach ($config as $column)
        <th class="p-2 border-b">{{ $column['label'] }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    {{-- Linhas com dados --}}
    @foreach ($data as $item)
      <tr class="border-b border-gray-700 text-gray-300 h-[60px]">
        @foreach ($config as $column)
          <td class="p-2 text-nowrap truncate h-full">
            @if (isset($column['render']))
              {!! $column['render']($item) !!}
            @else
              {{ data_get($item, $column['id']) }}
            @endif
          </td>
        @endforeach
      </tr>
    @endforeach

    {{-- Linhas vazias --}}
    @if ($data->count() < $data->perPage())
      @php
        $emptyRows = $data->perPage() - $data->count();
      @endphp
      @for ($i = 0; $i < $emptyRows; $i++)
        <tr class="border-b border-gray-700 text-gray-300 h-[60px]">
          @foreach ($config as $column)
            <td class="p-2 text-nowrap truncate h-full">&nbsp;</td>
          @endforeach
        </tr>
      @endfor
    @endif
  </tbody>
</table>

{{-- Paginação --}}
@if(method_exists($data, 'links'))
  <div class="mt-4 flex justify-between items-center border-t border-gray-700 pt-2">

    <div>
      <span class="text-gray-400">{{ $itemCountText }}: {{ $data->total() }}</span>
    </div>

    {{-- Seleção de Itens por Página --}}
    <div class="flex items-center gap-2">
      <span>Exibir:</span>
      <select wire:model.live="perPage" class="p-1 bg-gray-700 text-white rounded-md">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="50">50</option>
      </select>
      <span>itens por página</span>
    </div>

    {{-- Controles de Paginação --}}
    <div class="flex items-center gap-4">
      <x-button
        click="$wire.previousPage()"
        wire:loading.attr="disabled"
        icon="o-arrow-left"
        :disabled="$data->onFirstPage()"
      />

      <span>Página {{ $data->currentPage() }} de {{ $data->lastPage() }}</span>

      <x-button
        click="$wire.nextPage()"
        wire:loading.attr="disabled"
        icon="o-arrow-right"
        :disabled="$data->onLastPage()"
      />
    </div>
  </div>
@endif
