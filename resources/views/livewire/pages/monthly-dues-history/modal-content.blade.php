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
        @case('detail')
            <livewire:pages.monthly-dues-history.modal-content.detail :id="$id" lazy />
            @break
            
        @case('merge-monthly-dues')
            <livewire:pages.monthly-dues-history.modal-content.merge-monthly-dues lazy />
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
