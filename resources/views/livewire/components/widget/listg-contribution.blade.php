<?php
use App\Services\ComponentService;

use function Livewire\Volt\{state, mount};

state('value')->modelable();

state([
    // attributes
    'id' => null,
    'class' => null,

    'items' => [],
]);

mount(function (ComponentService $service) {
    $items = array_to_object($service->getContributionList()->toArray());
    $this->items = $items;
});

?>

<div>
    <div 
        @if ($id != null) id="{{ $id }}" @endif 
        class="card shadow-none {{ $class ?? '' }}">
        <div class="card-header text-left border-bottom px-0">
            {{ __('contribution.label_list') }} <small class="text-danger">*</small>
        </div>
        <div class="card-body border-bottom px-2">
            @if ($items)
            <div class="scroll-area-md">
                <div class="scrollbar-container ps--active-y">
                    <div class="list-group px-1 w-100">
                        @foreach ($items as $key => $item)
                            <label for="contribution-{{ $key }}" class="list-group-item list-group-item-action my-1 rounded {{ in_array($item->id, (array) $value) ? 'active' : '' }}" style="cursor: pointer;">
                                <div class="d-flex justify-content-between w-100">
                                    <div class="custom-control custom-checkbox d-none">
                                        <input type="checkbox" wire:model="value" class="custom-control-input contribution-listg" id="contribution-{{ $key }}" value="{{ $item->id }}" autocomplete="off">
                                    </div>
                                    <h5 class="align-self-center m-0 text-truncate fs-6 font-weight-bold w-100" title="{{ $item->name }}">{{ $item->name }}</h5>
                                    <div class="label-currency align-self-center fs-6 font-weight-bold {{ in_array($item->id, (array) $value) ? 'text-white' : 'text-success' }} text-right w-100">
                                        <small class="opacity-7 pr-1">Rp</small>
                                        {{ number_format($item->amount, 0, ',', '.') }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="text-center text-secondary font-weight-bold">
                {{ __('contribution.label_not_found') }}
            </div>
            @endif
        </div>
    </div>
</div>

@script
    <script>
        $(document).on("change", ".contribution-listg", function (e) {
            let t = $(e.currentTarget);
            let isChecked = t.prop("checked");
            let listGroup = t.closest(".list-group-item");
            if (isChecked) {
                listGroup.addClass("active");
                listGroup.find(".label-currency").removeClass("text-success").addClass("text-white");
            } else {
                listGroup.removeClass("active");
                listGroup.find(".label-currency").removeClass("text-white").addClass("text-success");
            }
        });
    </script>
@endscript
