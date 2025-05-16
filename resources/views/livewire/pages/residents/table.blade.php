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
        'totalFiltered' => 0,
        'data' => []
    ],

    'rowsNumber' => 0,
]);

placeholder('components.table-onload');

on(['loadData', 'toPage']);

$loadData = action(function () {
    info('load data residents');
    $this->isLoading = true;
    
    sleep(2);

    $this->table->total = 15;
    $this->table->totalFiltered = 15;
    $this->table->maxPage = ceil($this->table->totalFiltered / $this->table->perpage);

    $this->isLoading = false;
    $this->generateRowsNumber();
});

$toPage = action(function ($page) {
    $this->isLoading = true;
    $this->table->page = $page;
    $this->table->prev = $page - 1 == 0 ? 0 : $page - 1;
    $this->table->next = $page + 1;
    if ($page != 0 || $page <= $this->table->maxPage) {
        $this->dispatch('loadData');
    }
});

$generateRowsNumber = function () {
    if ($this->table->totalFiltered == 0) {
        $this->rowsNumber = 0;
        return;
    }

    $start = ($this->table->page * $this->table->perpage) - $this->table->perpage + 1;
    $end = $this->table->page * $this->table->perpage;
    if ($end > $this->table->totalFiltered) {
        $end = $this->table->totalFiltered;
    }
    $this->rowsNumber = $start . ' - ' . $end;
}

// $setDeviceType = action(function ($type) {
//     info('set device type: ' . $type);
//     $this->device = $type;
//     $this->isLoading = true;
//     sleep(2);
//     // $this->isLoading = false;
//     $this->dispatch('loadData');
// });

?>

<div>
    <div class="filter-card mb-3 card">
        <div class="card-header d-flex justify-content-between align-items-center px-3">
            <div class="float-left">
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary" data-toggle="collapse" href="#filter-collapse">
                        <i class="pe-7s-filter btn-icon-wrapper"></i>
                    </button>
                    
                    <button 
                        type="button" 
                        class="d-none d-md-inline-block btn-icon btn btn-success"
                        wire:click="$dispatch('openModalResident', { type: 'add' })">
                        <i class="pe-7s-plus btn-icon-wrapper"></i>
                        {{ __('label.add') }}
                    </button>

                    <button 
                        type="button"
                        class="d-inline-block d-md-none btn-icon btn-icon-only btn btn-success"
                        wire:click="$dispatch('openModalResident', { type: 'add' })">
                        <i class="pe-7s-plus btn-icon-wrapper"> </i>
                    </button>
            </div>
            <div class="float-right">
                <small class="font-weight-bold text-primary">{{ $rowsNumber }} / {{ $table->totalFiltered }}</small>
                <div class="btn-group" role="group" aria-label="pagination">
                    <button wire:click="$dispatch('toPage', {'page': {{ $table->prev }}})" type="button" class="btn-icon btn-icon-only btn btn-link" @if ($table->prev == 0) disabled @endif>
                        <i class="fa fa-angle-left btn-icon-wrapper"></i>
                    </button>
                    <button wire:click="$dispatch('toPage', {'page': {{ $table->next }}})" type="button" class="btn-icon btn-icon-only btn btn-link" @if ($table->next > $table->maxPage) disabled @endif>
                        <i class="fa fa-angle-right btn-icon-wrapper"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body collapse" id="filter-collapse">
            
        </div>
    </div>

    <div class="mb-3 card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table">
                    <thead>
                        <tr>
                            <th class="text-black-50 border-0"></th>
                            {{-- <th class="text-black-50 border-0">#</th> --}}
                            <th class="text-black-50 border-0">{{ __('resident.housing_block_table_label') }}</th>
                            <th class="text-black-50 border-0">{{ __('resident.name_table_label') }}</th>
                            <th class="text-black-50 border-0">{{ __('resident.phone_number_table_label') }}</th>
                            <th class="text-black-50 border-0">{{ __('resident.unique_code_table_label') }}</th>
                        </tr>
                    </thead>
                    <tbody wire:init="loadData">
                        @if($isLoading)
                            {{-- <x-tbody-onload :rows="5" :cols="6" /> --}}
                            <x-tbody-onload :cols="6" />
                        @else
                            @if (count($table->data) > 0)
                                
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">
                                        {{ __('label.datatable_not_found') }} 
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