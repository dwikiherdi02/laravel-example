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
            <livewire:pages.users.modal-content.detail :id="$id" lazy />
            @break

        @default
        {{-- <x-loading /> --}}
    @endswitch
</div>

@script
    <script>
        window.addEventListener('fetchModalUserContentJs', function handler(e) {
            const type = e.detail != undefined ? e.detail.type : null;
            const id = e.detail != undefined ? e.detail.id : null;

            let opt = {
                type: type,
                id: id
            };
            $wire.dispatch("setState", opt);

            // if (type == null) {
            //     $wire.set('type', type);
            // } else {
            //     $wire.set('id', id)
            //         .then(() => {
            //             $wire.set('type', type);
            //         });
            // }

        });
    </script>
@endscript
