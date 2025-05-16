<?php

use function Livewire\Volt\{state, on, action};

state(['type', 'animate']);

on(['openModalResident', 'closeModalResident']);

$openModalResident = action(function ($type) {
    info('open modal resident');
    $this->type = $type;
    $this->animate = 'animate__animated animate__fadeInRight animate__faster';
    // $this->dispatch('openModalResidentJs', type: $this->type, test: 'haha');
    $this->dispatch('openModalResidentJs');
});

$closeModalResident = action(function () {
    info('close modal resident');
    $this->type = '';
    $this->animate = 'animate__animated animate__fadeOutRight animate__faster';
    // $this->dispatch('closeModalResidentJs');
});

?>

<div>
    <div class="modal modal-fullscreen fade" id="modal-resident" tabindex="-1" role="dialog" aria-labelledby="modal-resident-title" aria-hidden="true" data-animate-in="animate__animated animate__fadeInRight animate__faster" data-animate-out="animate__animated animate__fadeOutRight animate__faster">
        <div class="modal-dialog modal-dialog-centered shadow-none {{ $animate }}" role="document">
            <div class="modal-content">
                @switch($type)
                    @case('add')
                        <livewire:pages.residents.modal.add lazy />
                        @break
                    @case('view')
                        <livewire:pages.residents.modal.view lazy />
                        @break
                    @case('edit')
                        <livewire:pages.residents.modal.edit lazy />
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
            console.log(event);
            $('#modal-resident').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });
    
        $wire.on('closeModalResidentJs', () => {
            $('#modal-resident').modal('hide');
            $wire.dispatch('closeModalResident');
        });
    });
</script>
@endscript
