<?php

use App\Services\ComponentService;

use function Livewire\Volt\{state, mount};

state([
    // attributes
    'model' => null,
    'id' => null,
    'class' => null,

    // data
    'opt' => []
]);

mount(function (ComponentService $service) {
    $opt = array_to_object($service->getRoleOptions()->toArray());
    $this->opt = $opt;
});

?>

<div>
    <x-select-list 
        wire:model="model" 
        :id="$id"
        class="{{ $class }}"
        >
        <option value="">-- {{ __('label.widget_select_role_placeholder') }} --</option>
        @foreach ($opt as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
    </x-select-list>
</div>
