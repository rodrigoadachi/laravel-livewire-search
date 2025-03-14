@props(['title', 'show', 'class' => '', 'width' => '25%'])

@if($show)
  <div
    class="fixed inset-0 flex items-center justify-center z-50 h-full w-full"
    style="background-color: rgba(0, 0, 0, 0.25);"
  >
    <div
      class="bg-zinc-900 text-white p-6 rounded-md shadow-lg relative border-zinc-400 border-[0.5px]"
      style="width: {{ $width }};"
    >
      <h2 class="text-lg font-bold">{{ $title }}</h2>

      <div class="mt-4 {{ $class }}">
        {{ $slot }}
      </div>
    </div>
  </div>
@endif
