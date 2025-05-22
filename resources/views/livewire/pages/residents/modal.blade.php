<?php

use function Livewire\Volt\{state, on, action};

state(['type', 'id', 'animate']);

on(['openModalResident', 'closeModalResident']);

$openModalResident = action(function ($type, $id = null) {
    info('open modal resident');
    $this->type = $type;
    $this->id = $id;
    $this->animate = 'animate__animated animate__fadeInRight animate__faster';
    // $this->dispatch('openModalResidentJs', type: $this->type, test: 'haha');
    $this->dispatch('openModalResidentJs');
});

$closeModalResident = action(function () {
    info('close modal resident');
    $this->type = '';
    $this->animate = 'animate__animated animate__fadeOutRight animate__faster';
});

?>

<div>
    <div class="modal modal-fullscreen px-0 fade" id="modal-resident" tabindex="-1" role="dialog" aria-labelledby="modal-resident-title" aria-hidden="true" data-animate-in="animate__animated animate__fadeInRight animate__faster" data-animate-out="animate__animated animate__fadeOutRight animate__faster">
        <div class="modal-dialog modal-dialog-centered shadow-none {{ $animate }}" role="document">
            <div class="modal-content overflow-hidden">
                @switch($type)
                    @case('add')
                        <livewire:pages.residents.modal.add lazy />
                        @break
                    @case('edit')
                        <livewire:pages.residents.modal.edit :id="$id" lazy />
                        @break
                    @default
                @endswitch
            </div>
        </div>
    </div>
</div>

@script
<script>
    $(function () {
        $wire.on('openModalResidentJs', (event) => {
            $('#modal-resident').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            window.dispatchEvent(new Event('residentModalOpened'));
        });

        $wire.on('closeModalResidentJs', (event) => {
            if (event && event.reloadTable == true) {
                window.dispatchEvent(new Event('reloadDataResident'));
                setTimeout(() => {
                    $('#modal-resident').modal('hide');
                    $wire.dispatch('closeModalResident');
                }, 500);
            } else {
                $('#modal-resident').modal('hide');
                $wire.dispatch('closeModalResident');
            }
        });
    });
</script>
@endscript
