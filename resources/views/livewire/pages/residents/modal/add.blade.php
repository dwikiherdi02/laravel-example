<?php

use function Livewire\Volt\{placeholder, state};

placeholder('components.loading');

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('resident.add_label') }}</h5>
        <button wire:click="$dispatch('closeModalResidentJs')" type="button" class="close" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form wire:submit.prevent=""></form>
    </div>
    <div class="modal-footer bg-transparent border-0 d-flex justify-content-between">
        <button wire:click="$dispatch('closeModalResidentJs')" type="button" class="btn btn-lg btn-secondary w-100">{{ __('label.cancel') }}</button>
        <button type="button" class="btn btn-lg btn-primary w-100">{{ __('label.save') }}</button>
    </div>
</div>
