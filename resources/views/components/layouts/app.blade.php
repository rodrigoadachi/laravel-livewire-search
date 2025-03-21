<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Livewire' }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
  </head>
  <body
    class="min-h-screen min-w-screen h-full w-full flex flex-col items-start justify-start bg-zinc-800 text-zinc-200"
  >
    <x-global.header />

    <main
      class="container h-full w-full overflow-x-hidden overflow-y-auto flex flex-col items-start justify-start bg-zinc-800 text-zinc-200 relative"
    >
      {{ $slot }}
      <x-loading />
      <x-alert />
    </main>

    @livewireScripts
    @vite(['resources/js/app.js'])
  </body>
</html>
