<?php

use function Livewire\Volt\{state, on, action};

state([
    'type' => null,
    'id' => null,
    'year' => null,
    'month' => null,
]);

on(['setState']);

$setState = action(function (?string $type = null, ?string $id = null, ?int $year = null, ?int $month = null) {
    $this->id = $id;
    $this->type = $type;
    $this->year = $year;
    $this->month = $month;
});

?>


<div>
    @switch($type)
        @case('detail')
            <livewire:pages.monthly-dues-history.modal-content.detail :id="$id" lazy />
            @break

        @case('merge-monthly-dues')
            <livewire:pages.monthly-dues-history.modal-content.house-bill-merge :year="$year" :month="$month" lazy />
            @break

        @case('merge-mutli-month-dues')
            <livewire:pages.monthly-dues-history.modal-content.monthly-bill-merge lazy />
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

            let opt = {
                type: type,
                id: id,
                year: year,
                month: month
            };
            $wire.dispatch("setState", opt);
        });
    </script>
@endscript
