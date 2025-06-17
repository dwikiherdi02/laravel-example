<?php

use App\Dto\TextTemplateDto;
use App\Services\TextTemplateService;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{placeholder, state, rules};

placeholder('components.loading');

state([
    // form
    'name' => '',
    'transaction_type_id' => '',
    'email' => '',
    'email_subject' => '',
    'template' => '',

    // alert
    'alertMessage' => ''
]);

rules([
    'name' => ['required', 'string'],
    'transaction_type_id' => ['required', 'string'],
    'email' => ['required', 'email', 'string'],
    'email_subject' => ['required', 'string'],
    'template' => ['required', 'string'],
])->attributes([
            'name' => __('text-template.label_name'),
            'transaction_type_id' => __('text-template.label_trans_type'),
            'email' => __('text-template.label_email'),
            'email_subject' => __('text-template.label_email_subject'),
            'template' => __('text-template.label_template'),
        ]);

$createTextTemplate = function (TextTemplateService $service) {
    try {
        $this->alertMessage = null;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = TextTemplateDto::from($validated);

        $service->create($data);

        $this->dispatch('hideModalTextTemplateJs');
    } catch (ValidationException $e) {
        throw $e;
    } catch (\Exception $e) {
        $this->reset('name', 'transaction_type_id', 'email', 'email_subject', 'template');
        $this->alertMessage = $e->getMessage();
    }
};

$generateTemplate = function (\App\Libraries\Imap $imapLib) {
    try {
        $this->alertMessage = null;
        $this->template = $imapLib->generateTemplate($this->email, $this->email_subject);
    } catch (\Exception $e) {
        $this->alertMessage = $e->getMessage();
    }
};

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('text-template.label_add') }}</h5>
        <button wire:target="createTextTemplate, generateTemplate" wire:loading.attr="disabled" type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        <div class="alert alert-danger alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif"
            role="alert">
            {{ $alertMessage }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form wire:submit="createTextTemplate" id="user-form">
            <div class="row">
                <div class="form-group col-12 col-md-12 mb-3">
                    <x-input-label for="name" :value="__('text-template.label_name')" :isRequired="true" />
                    <x-text-input wire:model="name" id="name" type="text" name="name"
                        aria-describedby="nameHelp" />
                    <x-input-error id="nameHelp" :messages="$errors->get('name')" />
                </div>

                <div class="form-group col-12 col-md-12 mb-3">
                    <x-input-label for="transaction_type_id" :value="__('text-template.label_trans_type')" :isRequired="true" />
                    <livewire:components.widget.list-transaction-type wire:model="transaction_type_id" :id="'transaction-type'" :class="'select2'" :name="'transaction_type_id'" :ariaDescribedby="'transactionTypeHelp'">
                    <x-input-error id="transactionTypeHelp" :messages="$errors->get('transaction_type_id')" />
                </div>

                <div class="form-group col-12 col-md-12 mb-3">
                    <x-input-label for="email" :value="__('text-template.label_email')" :isRequired="true" />
                    <x-text-input wire:model="email" id="email" type="email" name="email"
                        aria-describedby="emailHelp" />
                    <x-input-error id="emailHelp" :messages="$errors->get('email')" />
                </div>

                <div class="form-group col-12 col-md-12 mb-3">
                    <x-input-label for="email_subject" :value="__('text-template.label_email_subject')" :isRequired="true" />
                    <x-text-input wire:model="email_subject" id="email_subject" type="text" name="email_subject"
                        aria-describedby="emailSubjectHelp" />
                    <x-input-error id="emailSubjectHelp" :messages="$errors->get('email_subject')" />
                </div>

                <div class="col-12 col-md-12 mb-3">
                    <a wire:click="generateTemplate" wire:target="generateTemplate" wire:loading.attr="disabled" class="btn btn-light">{{ __('text-template.label_generate_template') }}</a>
                </div>

                <div class="form-group col-12 mb-3">
                    <x-input-label for="template" :value="__('text-template.label_template')" :isRequired="true" />
                    <x-text-area wire:model="template" rows="1" id="template" class="autosize-input" style="min-height: 200px !important; max-height: 500px;" />
                    <x-input-error id="templateHelp" :messages="$errors->get('template')" />
                </div>

            </div>
        </form>
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        <button wire:target="createTextTemplate, generateTemplate" wire:loading.attr="disabled" type="button" class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0" data-dismiss="modal">
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

@script
<script>
    $(function () {
        generateScrollbar();
        generateTextareaAutosize();
    });
</script>
@endscript
