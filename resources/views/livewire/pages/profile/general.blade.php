<?php

use App\Services\ResidentService;
use App\Dto\ResidentDto;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{state, rules, mount};

state([
    'auth',
    
    // form
    'id',
    'housing_block' => '',
    'name' => '',
    'phone_number' => '',
    'unique_code' => '',
    'address' => '',

    // alert
    'alertMessage' => '',
    'isError' => false,
]);

rules([
    'id' => ['required', 'string', 'uuid'],
    'housing_block' => ['required', 'string'],
    'name' => ['required', 'string'],
    'phone_number' => ['required', 'string'],
    'unique_code' => [
        'required',
        'integer',
    ],
    'address' => ['nullable'],
]);

mount(function () {
    $resident = $this->auth->resident;
    if ($resident) {
        $this->id = $resident->id ?? '';
        $this->housing_block = $resident->housing_block ?? '';
        $this->name = $resident->name ?? '';
        $this->phone_number = $resident->phone_number ?? '';
        $this->unique_code = $resident->unique_code ?? 0;
        $this->address = $resident->address ?? '';
    }
});

$updateResident = function (ResidentService $service) {
    try {
        $this->alertMessage = null;
        $this->isError = false;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = ResidentDto::from($validated);

        $service->update($data);

        $this->alertMessage = __('resident.success_update');
    } catch (ValidationException $e) {
        $this->isError = true;
        throw $e;
    } catch (\Exception $e) {
        $this->isError = true;
        // $this->reset('housing_block', 'name', 'phone_number', 'unique_code', 'address');
        $this->alertMessage = $e->getMessage();
    }
}

?>

<div>
    <div class="alert @if($isError) alert-danger @else alert-success @endif alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
        {{ $alertMessage }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="general-form" wire:submit="updateResident">
        <div class="form-group row mb-4">
            <x-input-label for="housing_block" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('resident.label_housing_block')" />
            <div class="col-sm-8 text-right">
                <x-text-input 
                    wire:model="housing_block" 
                    :isNotFormControl="true" 
                    class="form-control-plaintext" 
                    id="housing_block" 
                    type="text" 
                    name="housing_block"  
                    readonly />
            </div>
        </div>

        <div class="form-group row mb-4">
            <x-input-label for="unique_code" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('resident.label_unique_code')" />
            <div class="col-sm-8 text-right">
                <x-text-input 
                    wire:model="unique_code" 
                    :isNotFormControl="true" 
                    class="form-control-plaintext" 
                    id="unique_code" 
                    type="text" 
                    name="unique_code"
                    readonly />
            </div>
        </div>

        <div class="form-group row mb-4">
            <x-input-label for="name" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('resident.label_name')" :isRequired="true" />
            <div class="col-sm-8 text-right">
                <x-text-input wire:model="name" id="name" type="text" name="name" aria-describedby="nameHelp" />
                <x-input-error class="text-left" id="nameHelp" :messages="$errors->get('name')" />
            </div>
        </div>

        <div class="form-group row mb-4">
            <x-input-label for="phone_number" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('resident.label_phone_number')" :isRequired="true" />
            <div class="col-sm-8 text-right">
                <x-text-input wire:model="phone_number" id="phone_number" class="input-mask-trigger" type="text" name="phone_number" placeholder="087781234567" aria-describedby="phoneNumberHelp" />
                <x-input-error class="text-left" id="phoneNumberHelp" :messages="$errors->get('phone_number')" />
            </div>  
        </div>

        <div class="form-group row mb-4">
            <x-input-label for="address" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('resident.label_address')" />
            <div class="col-sm-8 text-right">
                <x-text-area wire:model="address" id="address" class="autosize-input" style="min-height: 42px !important; max-height: 200px;" />
                <x-input-error class="text-left" id="addressHelp" :messages="$errors->get('address')" />
            </div>  
        </div>
        
        <div class="d-flext">
            <button wire:loading.remove type="submit" class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100">
                {{ __('label.save') }}
            </button>

            <button wire:loading class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
        </div> 
    </form>
</div>

@script
<script>
    $(function () {
        generateTextareaAutosize();
    });
</script>
@endscript