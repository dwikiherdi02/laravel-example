<?php

use App\Dto\DuesMonthDto;
use App\Services\ComponentService;
use App\Services\DuesMonthService;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{layout, state, rules, title, mount};

layout('layouts.app');

state([
    'title',
    'icon',

    // form
    'dues_date',
    'contribution_ids' => [],

    // alert
    'alertMessage' => '',
    'isError' => false,
]);

rules([
    'dues_date' => ['required', 'string'],
    'contribution_ids' => ['array'],
])->attributes([
    'dues_date' => trans('dues_month.label_dues_date'),
    'contribution_ids' => trans('contribution.label_list'),
]);

title(function (ComponentService $service) {
    $path = '/add-dues-month';
    $menu = $service->getMenuBySlug($path);
    $this->title = __($menu->name_lang_key ?? '');
    $this->icon = $menu->icon ?? '';
    return $this->title;
});

$createDuesMonth = function (DuesMonthService $service) {
    try {
        $this->isError = false;
        $this->alertMessage = null;
        
        $this->resetErrorBag();
        
        $validated = $this->validate(); 
        
        $data = DuesMonthDto::from($validated);
        
        $service->create($data);

        $date = Carbon::createFromFormat('m-Y', $data->dues_date);
        $params = [];

        if ($date && $date->format('m-Y') === $data->dues_date) {
            $params['year'] = $date->format('Y');
            $params['month'] = $date->format('m');
        }

        $this->redirect(route(
            'monthly-dues-history', 
            $params
        ));
    } catch (ValidationException $e) {
        $this->isError = true;
        throw $e;
    } catch (\Exception $e) {
        $this->isError = true;
        $this->alertMessage = $e->getMessage();
        // $this->reset('dues_date', 'contribution_ids');
    }
};

?>

<div>
    <x-page-heading :title="$title" :icon="$icon" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="header-icon lnr-file-add icon-gradient bg-deep-blue"></i>
                    {{ __('Formulir Iuran Bulanan') }}
                </div> 
                <div class="card-body">
                    <div class="alert @if($isError) alert-danger @else alert-success @endif alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
                        {{ $alertMessage }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="dues-month-form" wire:submit="createDuesMonth">
                        <div class="form-group row mb-4">
                            <x-input-label for="host" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('dues_month.label_dues_date')" :isRequired="true" />
                            <div class="col-sm-8">
                                <x-text-input wire:model="dues_date" id="dues-date" type="text" name="dues_date" autocomplete="off" aria-describedby="duesDateHelp" />
                                <x-input-error class="text-left" id="duesDateHelp" :messages="$errors->get('dues_date')" />
                            </div>
                        </div>
                        
                        <livewire:components.widget.listg-contribution wire:model="contribution_ids" :class="'mb-4'" /> 

                        <div class="d-flex">
                            <button wire:target="createDuesMonth" wire:loading.remove type="submit" class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100">
                                {{ __('label.save') }}
                            </button>

                            <button wire:target="createDuesMonth" wire:loading class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $(function () {
        const now = new Date();
        const minDate = new Date(now.getFullYear(), now.getMonth(), 1);

        $("#dues-date").datepicker({
            autoHide: true,
            // autoPick: true,
            format: 'mm-yyyy',
            language: 'id-ID',
            startView: 1,
            minView: 1,
            startDate: minDate,
        });

        $("#dues-date").on('pick.datepicker', function (e) {
            $wire.set('isError', false);
            $wire.set('alertMessage', null);
            $wire.set('dues_date', moment(e.date).format('MM-YYYY'));
        });
    });
</script>
@endscript