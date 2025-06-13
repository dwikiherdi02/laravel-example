<?php

use function Livewire\Volt\{placeholder, state};

placeholder('components.loading');

state([
    'id',
    // 'item' => null,

    // alert
    'alertMessage' => '',
    'isError' => false,
]);

// mount(function (DuesPaymentService $service) {
//     $this->item = $service->findById($this->id);
// });

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('Tandai Sudah Bayar') }}</h5>
        <button wire:target="createMarkAsPaid" wire:loading.attr="disabled" type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        <div class="alert @if($isError) alert-danger @else alert-success @endif alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
            {{ $alertMessage }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form wire:submit="createMarkAsPaid" id="user-form">
            <div class="row">

                <div class="form-group col-12 col-md-12 mb-3">
                    <x-input-label for="transaction_method_id" :value="__('Metode Transaksi')" :isRequired="true" />
                    <livewire:components.widget.list-transaction-method wire:model="transaction_method_id" :id="'transaction-method'" :class="'select2'" :name="'transaction_method_id'" :ariaDescribedby="'transactionMethodHelp'">
                    <x-input-error id="transactionMethodHelp" :messages="$errors->get('transaction_method_id')" />
                </div>
                
            </div>
        </form>
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        <button wire:target="createMarkAsPaid" wire:loading.attr="disabled" type="button" class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0" data-dismiss="modal">
            {{ __('label.cancel') }}
        </button>

        <button wire:loading.remove type="submit" form="user-form" class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">
            {{ __('label.save') }}
        </button>
        <button wire:loading class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>
    </div>
</div>