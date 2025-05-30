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
            <livewire:pages.text-template.modal-content.add lazy />
            @break

        @case('edit')
            <livewire:pages.text-template.modal-content.edit :id="$id" lazy />
            @break

        @default
    @endswitch
</div>

@script
    <script>
        window.addEventListener('fetchModalTextTemplateContentJs', function handler(e) {
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
