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
                <button type="button" class="btn-icon btn-icon-only btn btn-primary" data-toggle="collapse" href="#filter-collapse">
                    <i class="pe-7s-filter btn-icon-wrapper"></i>
                </button>
            </div>
            <div class="float-right">
                <small class="font-weight-bold text-primary">1-10 / 2,000</small>
                <div class="btn-group" role="group" aria-label="sort">
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

    <div class="mb-3 card" data-device="desktop">
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
                    <tbody>
                        @if($isLoading)
                            {{-- <x-tbody-onload :rows="5" :cols="6" /> --}}
                            <x-tbody-onload :cols="6" />
                        @else
                            <tr>
                                <td>-</td>
                                {{-- <td>1</td> --}}
                                <td>BLOK 1</td>
                                <td>NAMA 1</td>
                                <td>0213456789</td>
                                <td>20</td>
                            </tr>
                            <tr>
                                <td>-</td>
                                {{-- <td>2</td> --}}
                                <td>BLOK 2</td>
                                <td>NAMA 2</td>
                                <td>0213456789</td>
                                <td>21</td>
                            </tr>
                            <tr>
                                <td>-</td>
                                {{-- <td>3</td> --}}
                                <td>BLOK 3</td>
                                <td>NAMA 3</td>
                                <td>0213456789</td>
                                <td>22</td>
                            </tr>
                            <tr>
                                <td>-</td>
                                {{-- <td>4</td> --}}
                                <td>BLOK 4</td>
                                <td>NAMA 4</td>
                                <td>0213456789</td>
                                <td>23</td>
                            </tr>
                            <tr>
                                <td>-</td>
                                {{-- <td>5</td> --}}
                                <td>BLOK 5</td>
                                <td>NAMA 5</td>
                                <td>0213456789</td>
                                <td>24</td>
                            </tr>
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