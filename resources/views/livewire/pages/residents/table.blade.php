<?php

use function Livewire\Volt\{placeholder, state, on, action};

state([
    'isLoading' => true,
    // 'device' => 'desktop',
    
    'table' => (object) [
        'filter' => '',
        'perpage' => 10,
        'page' => 1,
        'total' => 0,
        'totalFiltered' => 0,
        'data' => []
    ],
]);

placeholder('components.table-onload');

on(['loadData']);

$loadData = action(function () {
    info('load data residents');
    $this->isLoading = true;
    sleep(2);
    $this->isLoading = false;
    // $this->dispatch('generate-table');
});

// $setDeviceType = action(function ($type) {
//     info('set device type: ' . $type);
//     $this->device = $type;
//     $this->isLoading = true;
//     sleep(2);
//     // $this->isLoading = false;
//     $this->dispatch('loadData');
// });

?>

<div wire:init='loadData'>
    <div class="filter-card mb-3 card">
        <div class="card-header d-flex justify-content-between align-items-center px-3">
            <div class="float-left">
                {{-- <div class="btn-group" role="group" aria-label="pagination"> --}}
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary" data-toggle="collapse" href="#filter-collapse">
                        <i class="pe-7s-filter btn-icon-wrapper"></i>
                    </button>
                    
                    <button 
                        type="button" 
                        class="d-none d-md-inline-block btn-icon btn btn-success"
                        data-toggle="modal" data-target="#exampleModalCenter">
                        <i class="pe-7s-plus btn-icon-wrapper"></i>
                        {{ __('label.add') }}
                    </button>

                    <button 
                        type="button"
                        class="d-inline-block d-md-none btn-icon btn-icon-only btn btn-success"
                        data-toggle="modal" data-target="#exampleModalCenter">
                        <i class="pe-7s-plus btn-icon-wrapper"> </i>
                    </button>
                {{-- </div> --}}
            </div>
            <div class="float-right">
                <small class="font-weight-bold text-primary">1-10 / 2,000</small>
                <div class="btn-group" role="group" aria-label="pagination">
                    <button type="button" class="btn-icon btn-icon-only btn btn-link">
                        <i class="fa fa-angle-left btn-icon-wrapper"></i>
                    </button>
                    <button type="button" class="btn-icon btn-icon-only btn btn-link">
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

@script
<script>
    $(document).ready(function () {
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
    }

</script>
@endscript