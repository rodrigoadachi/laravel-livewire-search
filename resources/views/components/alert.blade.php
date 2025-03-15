<div
  x-data="{
    alerts: [],
    addAlert(alert) {
      this.alerts.push(alert);
      setTimeout(() => {
        this.alerts.shift();
      }, 5000);
    }
  }"
  x-on:show-alerts.window="addAlert($event.detail.alerts[0])"
  class="fixed bottom-5 right-5 flex flex-col gap-3 z-50"
>
  <template x-for="(alert, index) in alerts" :key="index">
    <div class="relative px-6 py-3 rounded-md shadow-md text-white overflow-hidden inline-flex items-center justify-start gap-2"
      :class="{
        'bg-green-500': alert.type === 'success',
        'bg-red-500': alert.type === 'error',
        'bg-yellow-500': alert.type === 'warning',
        'bg-gray-700': alert.type === 'info'
      }"
    >

      <x-dynamic-component x-show="alert.type === 'success'" component="heroicon-o-check-circle" class="w-6 h-6" />
      <x-dynamic-component x-show="alert.type === 'error'" component="heroicon-o-x-circle" class="w-6 h-6" />
      <x-dynamic-component x-show="alert.type === 'warning'" component="heroicon-o-exclamation-circle" class="w-6 h-6" />
      <x-dynamic-component x-show="alert.type === 'info'" component="heroicon-o-information-circle" class="w-6 h-6" />

      <span x-text="alert.message"></span>

      <button class="absolute top-1 right-1 text-white" @click="alerts.splice(index, 1)">
        &times;
      </button>

      <div class="absolute bottom-0 left-0 h-2 bg-white opacity-50"
        style="width: 100%; transition: width 5s linear;"
        x-init="$el.style.width = '0%'">
      </div>
    </div>
  </template>
</div>
