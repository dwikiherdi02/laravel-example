<?php

use App\Services\ResidentService;
use App\Dto\ResidentDto;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{placeholder, state, rules, mount};

placeholder('components.loading');

state([
    // form
    'id',
    'housing_block' => '',
    'name' => '',
    'phone_number' => '',
    'unique_code' => '',
    'address' => '',

    // alert
    'alertMessage' => '',
]);

rules([
    'id' => ['required', 'string', 'uuid'],
    'housing_block' => ['required', 'string'],
    'name' => ['required', 'string'],
    'phone_number' => ['required', 'string'],
    'unique_code' => [
        'required',
        'integer',
        // Rule::unique('residents')->where(function ($query) {
        //     return $query->where('deleted_at', null);
        //                 // ->where('id', '!=', state('id'));
        // })
    ],
    'address' => ['nullable'],
]);

mount(function (ResidentService $service) {
    if ($this->id != null) {
        $resident = $service->findById($this->id);
        $this->id = $resident->id;
        $this->housing_block = $resident->housing_block;
        $this->name = $resident->name;
        $this->phone_number = $resident->phone_number;
        $this->unique_code = $resident->unique_code;
        $this->address = $resident->address;
    }
});

$updateResident = function (ResidentService $service) {
    try {
        $this->alertMessage = null;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = ResidentDto::from($validated);

        $service->update($data);

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
        <h5 class="modal-title" id="modal-resident-title">{{ __('resident.label_edit') }}</h5>
        <button wire:click="$dispatch('closeModalResidentJs')" wire:target="updateResident" wire:loading.attr="disabled" type="button" class="close" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        @if($id != null)
        <div class="alert alert-danger alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
            {{ $alertMessage }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="resident-form" wire:submit="updateResident">
            <div class="row">
                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="housing_block" :value="__('resident.label_housing_block')" :isRequired="true" />
                    <x-text-input wire:model="housing_block" id="housing_block" type="text" name="housing_block" aria-describedby="housingBlockHelp" readonly />
                    <x-input-error id="housingBlockHelp" :messages="$errors->get('housing_block')" />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="name" :value="__('resident.label_name')" :isRequired="true" />
                    <x-text-input wire:model="name" id="name" type="text" name="name"
                        aria-describedby="nameHelp" />
                    <x-input-error id="nameHelp" :messages="$errors->get('name')" />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="phone_number" :value="__('resident.label_phone_number')" :isRequired="true" />
                    <x-text-input wire:model="phone_number" id="phone_number" class="input-mask-trigger" type="text" name="phone_number" placeholder="087781234567" aria-describedby="phoneNumberHelp" />
                    <x-input-error id="phoneNumberHelp" :messages="$errors->get('phone_number')" />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="unique_code" :value="__('resident.label_unique_code')" :isRequired="true" />
                    <x-text-input wire:model="unique_code" id="unique_code" class="form-number text-right" type="number" name="unique_code" aria-describedby="uniqueCodeHelp" readonly />
                    <x-input-error id="uniqueCodeHelp" :messages="$errors->get('unique_code')" />
                </div>

                <div class="form-group col-12 mb-3">
                    <x-input-label for="address" :value="__('resident.label_address')" />
                    <x-text-area wire:model="address" rows="1" id="address" class="autosize-input" style="max-height: 200px;" />
                    <x-input-error id="addressHelp" :messages="$errors->get('address')" />
                </div>
            </div>
        </form>
        @else
        <div class="d-flex justify-content-center align-items-center" style="height: 82vh;">
            <div class="text-center w-100">
                <p class="h6">{{ __('resident.error_resident_not_found') }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        @if($id != null)
            <button wire:click="$dispatch('closeModalResidentJs')" wire:target="updateResident" wire:loading.attr="disabled" type="button" class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">{{ __('label.cancel') }}</button>
            <button wire:loading.remove type="submit" form="resident-form" class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">{{ __('label.save') }}</button>
            <button wire:loading class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
        @else
            <button wire:click="$dispatch('closeModalResidentJs')" type="button" class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">{{ __('label.cancel') }}</button>
        @endif
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
