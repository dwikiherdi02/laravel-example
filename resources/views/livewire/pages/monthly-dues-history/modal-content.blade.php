<?php

use function Livewire\Volt\{state, on, action};

state([
    'type' => null,
    'id' => null,
    'year' => null,
    'month' => null,
    'resident_id' => null,
]);

on(['setState']);

$setState = action(function (?string $type = null, ?string $id = null, ?int $year = null, ?int $month = null, ?string $resident_id = null) {
    $this->id = $id;
    $this->type = $type;
    $this->year = $year;
    $this->month = $month;
    $this->resident_id = $resident_id;
});

?>


<div>
    @switch($type)
        @case('detail')
            <livewire:pages.monthly-dues-history.modal-content.detail :id="$id" lazy />
            @break

        @case('houes-bill-merge')
            <livewire:pages.monthly-dues-history.modal-content.house-bill-merge :year="$year" :month="$month" lazy />
            @break

        @case('monthly-bill-merge')
            <livewire:pages.monthly-dues-history.modal-content.monthly-bill-merge :resident_id="$resident_id" lazy />
            @break

        @default
    @endswitch
</div>

@script
    <script>
        window.addEventListener('fetchModalDuesHistoryContentJs', function handler(e) {
            const type = e.detail != undefined ? e.detail.type : null;
            const id = e.detail != undefined ? e.detail.id : null;
            const year = e.detail != undefined ? e.detail.year : null;
            const month = e.detail != undefined ? e.detail.month : null;
            const resident_id = e.detail != undefined ? e.detail.resident_id : null;

            let opt = {
                type: type,
                id: id,
                year: year,
                month: month,
                resident_id: resident_id,
            };
            $wire.dispatch("setState", opt);
        });
    </script>
@endscript
