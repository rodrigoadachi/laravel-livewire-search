<?php

namespace App\Livewire\Traits;

trait WithAlerts
{
  public function addAlert($message, $type = 'info')
  {
    $this->dispatch('show-alerts', alerts: [['message' => $message, 'type' => $type]]);
  }
}
