<div
  x-data="{ showLoading: false }"
  x-on:show-loading.window="
    showLoading = true;
  "
  x-on:hide-loading.window="
    showLoading = false;
  "
>
  <div
    x-show="showLoading"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    style="display: none;"
  >
    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
  </div>
</div>
