<?php

use App\Dto\TransactionDto;
use App\Services\ComponentService;
use App\Services\TransactionService;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{layout, state, title, rules};

layout('layouts.app');

state([
    'title',
    'icon',

    // form
    'name' => '',
    'transaction_type_id' => '',
    'transaction_method_id' => '',
    'base_amount' => 0,

    // alert
    'alertMessage' => '',
    'isError' => false,
]);

title(function (ComponentService $service) {
    $path = '/add-transaction';
    $menu = $service->getMenuBySlug($path);
    $this->title = __($menu->name_lang_key ?? '');
    $this->icon = $menu->icon ?? '';
    return $this->title;
});

rules([
    'name' => ['required', 'string', 'max:100'],
    'transaction_type_id' => ['required', 'string'],
    'transaction_method_id' => ['required', 'string'],
    'base_amount' => ['required', 'numeric', 'min:1'],
])->attributes([
    'name' => __('Nama Transaksi'),
    'transaction_type_id' => __('Tipe Transaksi'),
    'transaction_method_id' => __('Metode Transaksi'),
    'base_amount' => __('Nilai Transaksi'),
]);

$createTransaction = function (TransactionService $service) {
    try {
        $this->isError = false;
        $this->alertMessage = null;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = TransactionDto::from($validated);

        $service->create($data);

        $this->redirect(route( 'transaction-history'));
    } catch (ValidationException $e) {
        $this->isError = true;
        throw $e;
    } catch (\Exception $e) {
        $this->isError = true;
        $this->alertMessage = $e->getMessage();
    }
}

?>

<div>
    <x-page-heading :title="$title" :icon="$icon" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="header-icon lnr-file-add icon-gradient bg-deep-blue"></i>
                    {{ __('Formulir Kas Keluar/Masuk') }}
                </div>
                <div class="card-body">
                    <div class="alert @if($isError) alert-danger @else alert-success @endif alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
                        {{ $alertMessage }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="transaction-form" wire:submit="createTransaction">
                        <div class="form-group row mb-4">
                            <x-input-label for="name" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Nama Transaksi')" :isRequired="true" />
                            <div class="col-sm-8">
                                <x-text-input wire:model="name" id="name" type="text" name="name" autocomplete="off" aria-describedby="nameHelp" />
                                <x-input-error class="text-left" id="nameHelp" :messages="$errors->get('name')" />
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <x-input-label for="transaction-type" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Tipe Transaksi')" :isRequired="true" />
                            <div class="col-sm-8">
                                <livewire:components.widget.list-transaction-type wire:model="transaction_type_id" :id="'transaction-type'" :class="'select2'" :name="'transaction_type_id'" :ariaDescribedby="'transactionTypeHelp'">
                                <x-input-error id="transactionTypeHelp" :messages="$errors->get('transaction_type_id')" />
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <x-input-label for="transaction-method" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Metode Transaksi')" :isRequired="true" />
                            <div class="col-sm-8">
                                <livewire:components.widget.list-transaction-method wire:model="transaction_method_id" :id="'transaction-method'" :class="'select2'" :name="'transaction_method_id'" :ariaDescribedby="'transactionMethodHelp'">
                                <x-input-error id="transactionMethodHelp" :messages="$errors->get('transaction_method_id')" />
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <x-input-label for="base-amount" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Nilai Transaksi')" :isRequired="true" />
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-right-0">Rp</span>
                                    </div>
                                    <x-text-input wire:model="base_amount" id="base-base-amount" class="form-number text-right border-left-0" type="number" name="base_amount" aria-describedby="baseAmountHelp" />
                                </div>

                                <x-input-error id="baseAmountHelp" :messages="$errors->get('base_amount')" />
                            </div>
                        </div>

                        <div class="d-flex">
                            <button wire:target="createTransaction" wire:loading.remove type="submit" class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100">
                                {{ __('label.save') }}
                            </button>

                            <button wire:target="createTransaction" wire:loading class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
