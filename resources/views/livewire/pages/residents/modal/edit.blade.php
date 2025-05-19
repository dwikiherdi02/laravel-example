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
]);

rules([
    'id' => ['required', 'string', 'uuid'],
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

mount(function (ResidentService $service) {
    if ($this->id != null) {
        $resident = $service->findResidentById($this->id);
        $this->id = $resident->id;
        $this->housing_block = $resident->id;
        $this->name = $resident->id;
        $this->phone_number = $resident->id;
        $this->unique_code = $resident->id;
        $this->address = $resident->id;
    }
});

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('resident.edit_label') }}</h5>
        <button wire:click="$dispatch('closeModalResidentJs')" type="button" class="close" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        @if($id != null)
        ADA
        @else
        <div class="d-flex justify-content-center align-items-center" style="height: 82vh;">
            <div class="text-center w-100">
                <h5>Gagal ambil data. silahkan coba lagi atau hubungi admin.</h5>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer bg-transparent border-0 d-flex justify-content-between">
        @if($id != null)
            <button wire:click="$dispatch('closeModalResidentJs')" type="button" class="btn btn-lg btn-transition btn btn-outline-danger w-100">{{ __('label.cancel') }}</button>
            <button wire:loading.remove type="submit" form="resident-form" class="btn btn-lg btn-primary w-100">{{ __('label.save') }}</button>
            <button wire:loading class="btn btn-lg btn-primary w-100">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
        @else
            <button wire:click="$dispatch('closeModalResidentJs')" type="button" class="btn btn-lg btn-transition btn btn-outline-danger w-100">{{ __('label.cancel') }}</button>
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
