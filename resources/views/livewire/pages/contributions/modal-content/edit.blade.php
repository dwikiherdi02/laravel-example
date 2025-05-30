<?php

use App\Dto\ContributionDto;
use App\Services\ContributionService;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{placeholder, state, rules, mount};

placeholder('components.loading');

state([
    // form
    'id',
    'name' => '',
    'amount' => 0,
    
    // alert
    'alertMessage' => ''
]);

rules([
    'id' => ['required', 'string'],
    'name' => ['required', 'string', 'max:100'],
    'amount' => ['required', 'numeric', 'min:0'],
])->attributes([
    'name' => trans('contribution.label_name'),
    'amount' => trans('contribution.label_amount'),
]);

mount(function (ContributionService $service) {
    $contribution = $service->findById($this->id);
    if ($contribution) {
        $this->id = $contribution->id;
        $this->name = $contribution->name;
        $this->amount = $contribution->amount;
    } else {
        $this->id = null;
        $this->name = '';
        $this->amount = '';
    }
});

$updateContribution = function (ContributionService $service) {
    try {
        $this->alertMessage = null;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = ContributionDto::from($validated);

        $service->update($data);

        $this->dispatch('hideModalContributionJs');
    } catch (ValidationException $e) {
        throw $e;
    } catch (\Exception $e) {
        $this->reset('name', 'amount');
        $this->alertMessage = $e->getMessage();
    }
};

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('user.label_detail_user') }}</h5>
        <button type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        @if($id != null)
        <div class="alert alert-danger alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif"
            role="alert">
            {{ $alertMessage }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form wire:submit="updateContribution" id="user-form">
            <div class="row">
                <div class="form-group col-12 col-md-12 mb-3">
                    <x-input-label for="name" :value="__('contribution.label_name')" :isRequired="true" />
                    <x-text-input wire:model="name" id="name" type="text" name="name"
                        aria-describedby="nameHelp" />
                    <x-input-error id="nameHelp" :messages="$errors->get('name')" />
                </div>

                <div class="form-group col-12 col-md-12 mb-3">
                    <x-input-label for="amount" :value="__('contribution.label_amount')" :isRequired="true" />

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-right-0">Rp</span>
                        </div>
                        <x-text-input wire:model="amount" id="amount" class="form-number text-right border-left-0" type="number" name="amount" aria-describedby="amountHelp" />
                    </div>

                    <x-input-error id="amountHelp" :messages="$errors->get('amount')" />
                </div>
            </div>
        </form>
        @else
        <div class="d-flex justify-content-center align-items-center" style="height: 82vh;">
            <div class="text-center w-100">
                <p class="h6">{{ __('contribution.error_contribution_not_found') }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        @if($id != null)
        <button wire:target="updateContribution" wire:loading.attr="disabled" type="button" class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0" data-dismiss="modal">
            {{ __('label.cancel') }}
        </button>
        
        <button wire:loading.remove type="submit" form="user-form" class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">
            {{ __('label.save') }}
        </button>
        <button wire:loading class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>
        @else
        <button wire:target="updateContribution" wire:loading.attr="disabled" type="button" class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0" data-dismiss="modal">
            {{ __('label.cancel') }}
        </button>
        @endif
    </div>
</div>

@script
<script>
    $(function () {
        generateScrollbar();
    });
</script>
@endscript
