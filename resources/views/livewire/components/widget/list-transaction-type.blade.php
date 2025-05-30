<?php
use App\Services\ComponentService;

use function Livewire\Volt\{state, mount};

state('value')->modelable();

state([
    // attributes
    'withModel' => true,
    'id' => null,
    'class' => null,
    'name' => null,
    'ariaDescribedby' => null,

    // data
    'opt' => []
]);

mount(function (ComponentService $service) {
    $opt = array_to_object($service->getTransactionTypeOptions()->toArray());
    $this->opt = $opt;
});

?>

<div>
    @if ($withModel)
        <x-select-list wire:model="value" :id="$id" :name="$name" class="{{ $class }}"
            aria-describedby="{{ $ariaDescribedby }}">
            <option value="">{{ __('label.widget_select_transaction_type_placeholder') }}</option>
            @foreach ($opt as $item)
                <option value="{{ $item->id }}" data-name="{{ $item->name }}">
                    {{ $item->name }}
                </option>
            @endforeach
        </x-select-list>
    @else
        <x-select-list :id="$id" :name="$name" class="{{ $class }}" aria-describedby="{{ $ariaDescribedby }}">
            <option value="">{{ __('label.widget_select_transaction_type_placeholder') }}</option>
            @foreach ($opt as $item)
                <option value="{{ $item->id }}" data-name="{{ $item->name }}">
                    {{ $item->name }}
                </option>
            @endforeach
        </x-select-list>
    @endif
</div>
