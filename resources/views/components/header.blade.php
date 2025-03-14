@php
  $activeClass = 'text-zinc-50 border-b-2 border-primary inline-block pb-2';
  $inactiveClass = 'text-zinc-200 hover:text-zinc-400 transition-all duration-400';
  $menuItems = [
    'home' => 'Home',
    'categories' => 'Categorias',
    'brands' => 'Marcas'
  ];
@endphp

<header class="w-full bg-zinc-900 p-4 shadow-md flex items-center justify-center">
  <div class="mx-auto inline-flex items-center justify-between w-full h-full px-10">

    <a href="/" class="text-white text-lg font-bold">Laravel Livewire</a>

    <nav class="flex gap-6">
      @foreach ($menuItems as $route => $label)
        <a
          href="{{ route($route) }}"
          class="{{ request()->routeIs($route) ? $activeClass : $inactiveClass }}">
          {{ $label }}
        </a>
      @endforeach
    </nav>

  </div>
</header>
