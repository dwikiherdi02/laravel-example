<?php

use function Livewire\Volt\{state, on, action};

state([
    'type' => null,
    'id' => null,
]);

on(['setState']);

$setState = action(function (?string $type = null, ?string $id = null) {
    $this->id = $id;
    $this->type = $type;
});

?>


<div>
    @switch($type)
        @case('add')
            <livewire:pages.monthly-dues-history.modal-content.add lazy />
            @break

        @case('edit')
            <livewire:pages.monthly-dues-history.modal-content.edit :id="$id" lazy />
            @break

        @default
    @endswitch
</div>

@script
    <script>
        window.addEventListener('fetchModalDuesHistoryContentJs', function handler(e) {
            const type = e.detail != undefined ? e.detail.type : null;
            const id = e.detail != undefined ? e.detail.id : null;

            let opt = {
                type: type,
                id: id
            };
            $wire.dispatch("setState", opt);
        });
    </script>
@endscript
