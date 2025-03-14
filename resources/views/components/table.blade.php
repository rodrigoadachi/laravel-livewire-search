@props(['data', 'config'])

<table class="min-w-full border border-gray-700 rounded-md w-full">
  <thead class="bg-zinc-800 text-white">
    <tr>
      @foreach ($config as $column)
        <th class="p-2 border-b">{{ $column['label'] }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr class="border-b border-gray-700 text-gray-300">
        @foreach ($config as $column)
          <td class="p-2">
            @if (isset($column['render']))
              {!! $column['render']($item) !!}
            @else
              {{ data_get($item, $column['id']) }}
            @endif
          </td>
        @endforeach
      </tr>
    @endforeach
  </tbody>
</table>

{{-- Pagination --}}
@if(method_exists($data, 'links'))
  <div class="mt-4">
    {{ $data->links() }}
  </div>
@endif
