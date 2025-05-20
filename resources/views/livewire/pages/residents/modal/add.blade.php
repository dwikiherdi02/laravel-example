<?php

use App\Services\ResidentService;
use App\Dto\ResidentDto;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{placeholder, state, rules};

placeholder('components.loading');

state([
    // form
    'housing_block' => '',
    'name' => '',
    'phone_number' => '',
    'unique_code' => '',
    'address' => '',

    // alert
    'alertMessage' => ''
]);

rules([
    'housing_block' => ['required', 'string'],
    'name' => ['required', 'string'],
    'phone_number' => ['required', 'string'],
    'unique_code' => [
        'required',
        'integer',
        Rule::unique('residents')->where(function ($query) {
            return $query->where('deleted_at', null);
        })
    ],
    'address' => ['nullable'],
]);

$createResident = function (ResidentService $service) {
    try {
        $this->alertMessage = null;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = ResidentDto::from($validated);

        $service->create($data);

        $this->dispatch('closeModalResidentJs', reloadTable: true);
    } catch (ValidationException $e) {
        $this->reset('housing_block', 'name', 'phone_number', 'unique_code', 'address');
        throw $e;
    } catch (\Exception $e) {
        // $this->reset('housing_block', 'name', 'phone_number', 'unique_code', 'address');
        $this->alertMessage = $e->getMessage();
    }
}

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('resident.add_label') }}</h5>
        <button wire:click="$dispatch('closeModalResidentJs')" type="button" class="close" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        <div class="alert alert-danger alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
            {{ $alertMessage }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="resident-form" wire:submit="createResident">
            <div class="row">
                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="housing_block" :value="__('resident.housing_block_label')" :isRequired="true" />
                    <x-text-input wire:model="housing_block" id="housing_block" type="text" name="housing_block" aria-describedby="housingBlockHelp" />
                    <x-input-error id="housingBlockHelp" :messages="$errors->get('housing_block')" />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="name" :value="__('resident.name_label')" :isRequired="true" />
                    <x-text-input wire:model="name" id="name" type="text" name="name"
                        aria-describedby="nameHelp" />
                    <x-input-error id="nameHelp" :messages="$errors->get('name')" />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="phone_number" :value="__('resident.phone_number_label')" :isRequired="true" />
                    <x-text-input wire:model="phone_number" id="phone_number" class="input-mask-trigger" type="text" name="phone_number" placeholder="087781234567" aria-describedby="phoneNumberHelp" />
                    <x-input-error id="phoneNumberHelp" :messages="$errors->get('phone_number')" />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="unique_code" :value="__('resident.unique_code_label')" :isRequired="true" />
                    <x-text-input wire:model="unique_code" id="unique_code" class="form-number text-right" type="number" name="unique_code" aria-describedby="uniqueCodeHelp" />
                    <x-input-error id="uniqueCodeHelp" :messages="$errors->get('unique_code')" />
                </div>

                <div class="form-group col-12 mb-3">
                    <x-input-label for="address" :value="__('resident.address_label')" />
                    <x-text-area wire:model="address" rows="1" id="address" class="autosize-input" style="max-height: 200px;" />
                    <x-input-error id="addressHelp" :messages="$errors->get('address')" />
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer bg-transparent border-0 d-flex justify-content-between">
        <button wire:click="$dispatch('closeModalResidentJs')" type="button" class="btn btn-lg btn-transition btn btn-outline-danger w-100">{{ __('label.cancel') }}</button>
        <button wire:loading.remove type="submit" form="resident-form" class="btn btn-lg btn-primary w-100">{{ __('label.save') }}</button>
        <button wire:loading class="btn btn-lg btn-primary w-100">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>
    </div>
</div>

@script
<script>
    $(function () {
        // generateInputMask();
        generateScrollbar();
        generateTextareaAutosize();
    });
</script>
@endscript
