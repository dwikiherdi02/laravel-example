<?php

use function Livewire\Volt\{placeholder, state};

placeholder('components.loading');

state([
    'id'
]);


?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('Detail Pengguna') }}</h5>
        <button type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        ID: {{ $id }}
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        <button
            type="button"
            class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0"
            data-dismiss="modal">
            {{ __('label.close') }}
        </button>
    </div>
</div>
