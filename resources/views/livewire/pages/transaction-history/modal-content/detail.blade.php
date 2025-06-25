<?php

use App\Services\TransactionService;

use function Livewire\Volt\{placeholder, state, mount};

placeholder('components.loading');

state([
    'id',
    'item' => null
]);

mount(function (TransactionService $service) {
    $transaction = $service->findById($this->id, true);
    dd($transaction);
    if ($transaction) {
        $this->item = $transaction;
    }
});

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('user.label_detail_user') }}</h5>
        <button type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        @if($id != null && $item != null)
        ID: {{ $id }}
        @else
        <div class="d-flex justify-content-center align-items-center" style="height: 82vh;">
            <div class="text-center w-100">
                <p class="h6">{{ __('Data transaksi tidak ditemukan. silahkan coba lagi atau hubungi admin.') }}</p>
            </div>
        </div>
        @endif
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
