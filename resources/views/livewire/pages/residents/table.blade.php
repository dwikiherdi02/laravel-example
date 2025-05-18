<?php

use function Livewire\Volt\{placeholder, state, on, action};

state([
    'isLoading' => true,
    // 'device' => 'desktop',

    'table' => (object) [
        'filter' => '',
        'perpage' => 10,
        'prev' => 0,
        'page' => 1,
        'next' => 2,
        'maxPage' => 0,
        'total' => 0,
        'data' => [],
    ],

    'rowsNumber' => 0,
]);

placeholder('components.table-onload');

on(['loadDataResidents', 'toPageResident']);

$loadDataResidents = action(function ($page = null) {
    if ($page != null) {
        $this->table->page = $page;
        $this->table->prev = $page - 1 == 0 ? 0 : $page - 1;
        $this->table->next = $page + 1;
    }

    if ($page != 0 || $page <= $this->table->maxPage) {
        $this->load();
    }
});

$toPageResident = action(function ($page = null) {
    $this->isLoading = true;
    $this->dispatch('loadDataResidents', page: $page);
});

$load = function () {
    info('load data residents from load function');
    $this->isLoading = true;

    sleep(2);

    $this->table->total = 15;
    $this->table->maxPage = ceil($this->table->total / $this->table->perpage);

    $this->isLoading = false;
    $this->generateRowsNumber();
};

$generateRowsNumber = function () {
    if ($this->table->total == 0) {
        $this->rowsNumber = 0;
        return;
    }

    $start = $this->table->page * $this->table->perpage - $this->table->perpage + 1;
    $end = $this->table->page * $this->table->perpage;
    if ($end > $this->table->total) {
        $end = $this->table->total;
    }
    $this->rowsNumber = $start . ' - ' . $end;
};

?>

<div>
    <div class="filter-card mb-3 card">
        <div class="card-header d-flex justify-content-between align-items-center px-3 rounded-bottom">
            <div class="float-left">
                <button type="button" id="btn-filter-collapse" class="btn-icon btn-icon-only btn btn-primary">
                    <i class="pe-7s-filter btn-icon-wrapper"></i>
                </button>

                <button
                    type="button"
                    class="d-none d-md-inline-block btn-icon btn btn-success btn-add"
                    {{-- wire:click="$dispatch('openModalResident', { type: 'add' })" --}}
                    {{-- wire:loading.attr="disabled" --}}
                    >
                    <i class="pe-7s-plus btn-icon-wrapper"></i>
                    {{ __('label.add') }}
                </button>

                <button type="button" class="d-inline-block d-md-none btn-icon btn-icon-only btn btn-success btn-add"
                    {{-- wire:click="$dispatch('openModalResident', { type: 'add' })" --}}
                    {{-- wire:loading.attr="disabled" --}}
                    >
                    <i class="pe-7s-plus btn-icon-wrapper"> </i>
                </button>
            </div>
            <div class="float-right">
                <small class="font-weight-bold text-primary">{{ $rowsNumber }} / {{ $table->total }}</small>
                <div class="btn-group" role="group" aria-label="pagination">
                    <button wire:click="$dispatch('toPageResidentJs', {'page': {{ $table->prev }}})" type="button"
                        class="btn-icon btn-icon-only btn btn-link" @if ($table->prev == 0) disabled @endif>
                        <i class="fa fa-angle-left btn-icon-wrapper"></i>
                    </button>
                    <button wire:click="$dispatch('toPageResidentJs', {'page': {{ $table->next }}})" type="button"
                        class="btn-icon btn-icon-only btn btn-link" @if ($table->next > $table->maxPage) disabled @endif>
                        <i class="fa fa-angle-right btn-icon-wrapper"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body collapse" id="filter-collapse">

        </div>
    </div>

    <div class="mb-3 card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="mb-0 table">
                    {{-- <thead>
                        <tr>
                            <th class="text-black-50 border-0"></th>
                            <th class="text-black-50 border-0">{{ __('resident.housing_block_table_label') }}</th>
                            <th class="text-black-50 border-0">{{ __('resident.name_table_label') }}</th>
                            <th class="text-black-50 border-0">{{ __('resident.phone_number_table_label') }}</th>
                            <th class="text-black-50 border-0">{{ __('resident.unique_code_table_label') }}</th>
                        </tr>
                    </thead> --}}
                    <tbody wire:init="loadDataResidents">
                        @if ($isLoading)
                            {{-- <x-tbody-onload :rows="5" :cols="6" /> --}}
                            <x-tbody-onload :cols="2" />
                        @else
                            @if (count($table->data) > 0)
                            @else
                                {{-- <tr>
                                    <td colspan="6" class="text-center border-top-0">
                                        {{ __('label.datatable_not_found') }}
                                    </td>
                                </tr> --}}
                                <tr>
                                    <td class="border-top-0 border-bottom text-center align-middle" width="10%">
                                        <button type="button" class="btn-icon btn-icon-only btn btn-sm text-primary dropdown-toggle" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                            <span class="btn-icon-wrapper">
                                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                                            </span>                   
                                        </button>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-hover-link dropdown-menu"
                                            x-placement="bottom-start"
                                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-3px, 33px, 0px);">
                                            <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                            <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-inbox">
                                                </i><span>Menus</span></button>
                                            <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-file-empty">
                                                </i><span>Settings</span></button>
                                            <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-book">
                                                </i><span>Actions</span></button>
                                        </div>
                                    </td>
                                    <td class="border-top-0 border-bottom" width="90%">
                                        <h5 class="m-0">BJ 11</h5>
                                        <section class="mb-2"><small>Dwiki Herdiansyah / +6287782320192</small></section>
                                        <p>
                                            Dolor commodo aliquip amet eu voluptate nostrud nostrud aute nisi dolor culpa. Ut et sit consectetur sit excepteur veniam ad velit et laboris nostrud consectetur tempor. Officia est officia et anim nulla. Est esse aute eiusmod reprehenderit nisi magna dolore velit ipsum tempor eiusmod.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-0 border-bottom text-center align-middle" width="10%">
                                        <button type="button" class="btn-icon btn-icon-only btn btn-sm text-primary">
                                            <span class="btn-icon-wrapper">
                                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                                            </span>
                                        </button>
                                    </td>
                                    <td class="border-top-0 border-bottom" width="90%">
                                        <h5 class="m-0">BJ 12</h5>
                                        <section class="mb-2"><small>Ahmad / +6287782320193</small></section>
                                        <p>
                                            Dolor commodo aliquip amet eu voluptate nostrud nostrud aute nisi dolor culpa. Ut et sit consectetur sit excepteur
                                            veniam ad velit et laboris nostrud consectetur tempor. Officia est officia et anim nulla. Est esse aute eiusmod
                                            reprehenderit nisi magna dolore velit ipsum tempor eiusmod.
                                        </p>
                                    </td>
                                </tr>
                            @endif
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- @script
<script>
    /* $(document).ready(function () {
        emitDeviceType();
    });

    $(window).on('resize', function () {
        emitDeviceType();
    });

    function emitDeviceType() {
        let device = 'desktop';
        const width = window.innerWidth;

        // if (width <= 768) {
        //     device = 'mobile';
        // } else if (width <= 1024) {
        //     device = 'tablet';
        // }
        if (width <= 768) {
            device = 'mobile';
        }
        console.log('device type: ' + device);
        // Livewire.dispatch('setDeviceType', { type: device })
    } */
</script>
@endscript --}}

@script
    <script>
        $(function() {
            $wire.on('toPageResidentJs', (event) => {
                $wire.set('isLoading', true).then(() => {
                    $wire.dispatch('loadDataResidents', { page: event.page });
                });
            });
        });

        $("#btn-filter-collapse").on("click", () => {
            $("#filter-collapse").collapse("toggle");
        });

        $(".btn-add").on("click", () => {
            // alert("add button clicked");
            let $btn = $(".btn-add");
            $btn.prop("disabled", true);
            $wire.dispatch('openModalResident', { type: 'add' });
            // Enable tombol setelah event custom dari modal
            window.addEventListener('residentModalOpened', function handler() {
                $btn.prop("disabled", false);
                window.removeEventListener('residentModalOpened', handler);
            });
        });
    </script>
@endscript
