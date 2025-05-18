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
        'npage' => (object) [
            'prev' => 0,
            'current' => 1,
            'next' => 2,
            'max' => 0,
        ],
        'total' => 0,
        'data' => [],
    ],

    'page' => 0,
]);

placeholder('components.table-onload');

on(['loadDataResidents', 'toPageResident']);

$loadDataResidents = action(function ($page = null) {
    if ($page != null) {
        // $this->table->page = $page;
        // $this->table->prev = $page - 1 == 0 ? 0 : $page - 1;
        // $this->table->next = $page + 1;

        $this->table->npage->current = $page;
        $this->table->npage->prev = $page - 1 == 0 ? 0 : $page - 1;
        $this->table->npage->next = $page + 1;
    }

    if ($page != 0 || $page <= $this->table->npage->max) {
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
    $this->table->npage->max = ceil($this->table->total / $this->table->perpage);

    $this->isLoading = false;
    $this->generatePage();
};

$generatePage = function () {
    if ($this->table->total == 0) {
        $this->page = 0;
        return;
    }

    $start = $this->table->npage->current * $this->table->perpage - $this->table->perpage + 1;
    $end = $this->table->npage->current * $this->table->perpage;
    if ($end > $this->table->total) {
        $end = $this->table->total;
    }
    $this->page = $start . ' - ' . $end;
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
                    class="d-none d-md-inline-block btn-icon btn btn-success btn-add">
                    <i class="pe-7s-plus btn-icon-wrapper"></i>
                    {{ __('label.add') }}
                </button>

                <button 
                    type="button" 
                    class="d-inline-block d-md-none btn-icon btn-icon-only btn btn-success btn-add">
                    <i class="pe-7s-plus btn-icon-wrapper"> </i>
                </button>
            </div>
            <div class="float-right">
                <small class="font-weight-bold text-primary">{{ $page }} / {{ $table->total }}</small>
                <div class="btn-group" role="group" aria-label="pagination">
                    {{-- <button wire:click="$dispatch('toPageResidentJs', {'page': {{ $table->npage->prev }}})" type="button"
                        class="btn-icon btn-icon-only btn btn-link" @if ($table->npage->prev == 0) disabled @endif>
                        <i class="fa fa-angle-left btn-icon-wrapper"></i>
                    </button> --}}
                    {{-- <button wire:click="$dispatch('toPageResidentJs', {'page': {{ $table->npage->next }}})" type="button"
                        class="btn-icon btn-icon-only btn btn-link" @if ($table->npage->next > $table->npage->max) disabled @endif>
                        <i class="fa fa-angle-right btn-icon-wrapper"></i>
                    </button> --}} 
                    <button 
                        type="button"
                        class="btn-page btn-icon btn-icon-only btn btn-link" 
                        data-page="{{ $table->npage->prev }}"
                        @if ($table->npage->prev == 0) disabled @endif>
                        <i class="fa fa-angle-left btn-icon-wrapper"></i>
                    </button>
                    <button
                        type="button"
                        class="btn-page btn-icon btn-icon-only btn btn-link"
                        data-page="{{ $table->npage->next }}"
                        @if ($table->npage->next > $table->npage->max) disabled @endif>
                        <i class="fa fa-angle-right btn-icon-wrapper"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body collapse" id="filter-collapse">

        </div>
    </div>

    <div wire:init="loadDataResidents" class="mb-3 card">
        @if($isLoading)
            <x-loading :fullscreen="false" class="p-3" />
        @else
            {{-- @if (count($table->data) > 0) --}}
                <ul class="list-group list-group-flush">
                    {{-- <li class="list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">BJ 11</div>
                                    <div class="widget-subheading"><small>Dwiki Herdiansyah / +6287782320192</small></div>
                                    <p class="text-justify">
                                        Non eiusmod sunt nostrud reprehenderit Lorem. Pariatur et labore sint pariatur laboris. Aliquip anim elit tempor ipsum officia minim anim. Magna nisi deserunt laborum laboris ad exercitation adipisicing elit officia Lorem. Incididunt est elit anim tempor eu ea amet anim excepteur culpa nisi cupidatat. Officia deserunt nisi laboris enim incididunt.
                                    </p>
                                </div>
                                <div class="widget-content-left mr-3"></div>
                                <div class="widget-content-right">
                                    <button class="border-0 btn-transition btn btn-outline-warning">
                                        <i class="fa fa-pen"></i>
                                    </button>
                                    <button class="border-0 btn-transition btn btn-outline-danger">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li> --}}
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="text-left w-75">
                                <p class="h6 text-dark my-0">Blok: BJ 11</p>
                                <small class="text-muted">Dwiki Herdiansyah / +6287782320192</small>
                            </div>
                            <div class="text-right w-25 align-self-start">
                                <div class="d-inline-block dropdown">
                                    <button type="button"
                                        aria-haspopup="true" data-toggle="dropdown" aria-expanded="false"
                                        class="border-0 btn-transition btn btn-sm btn-link">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                        <button type="button" tabindex="0" class="dropdown-item">Ubah</button>
                                        <div tabindex="-1" class="dropdown-divider"></div>
                                        <button type="button" tabindex="0" class="dropdown-item">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="show-more-container" x-data="{ open: false }">
                            <p class="text-secondary text-justify my-0 show-more-text" :style="open ? 'max-height:none;overflow:visible;' : 'max-height:60px;overflow:hidden;'">
                                Non eiusmod sunt nostrud reprehenderit Lorem. Pariatur et labore sint pariatur laboris. Aliquip anim elit tempor ipsum officia minim anim. Magna nisi deserunt laborum laboris ad exercitation adipisicing elit officia Lorem. Incididunt est elit anim tempor eu ea amet anim excepteur culpa nisi cupidatat. Officia deserunt nisi laboris enim incididunt.
                            </p>
                            <a href="javascript:void(0);" 
                            class="show-more-link text-primary text-decoration-none"
                            style="font-size: 0.9em;"
                            x-text="open ? 'Sembunyikan' : 'Selengkapnya'"
                            x-on:click="open = !open">Selengkapnya</a>                  
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="text-left w-75">
                                <p class="h6 text-dark my-0">Blok: BJ 12</p>
                                <small class="text-muted">Fajar Eka Putra / +6287782320194</small>
                            </div>
                            <div class="text-right w-25 align-self-start">
                                <div class="d-inline-block dropdown">
                                    <button type="button"
                                        aria-haspopup="true" data-toggle="dropdown" aria-expanded="false"
                                        class="border-0 btn-transition btn btn-sm btn-link">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                        <button type="button" tabindex="0" class="dropdown-item">Ubah</button>
                                        <div tabindex="-1" class="dropdown-divider"></div>
                                        <button type="button" tabindex="0" class="dropdown-item">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="show-more-container" x-data="{ open: false }">
                            <p class="text-secondary text-justify my-0 show-more-text" :style="open ? 'max-height:none;overflow:visible;' : 'max-height:60px;overflow:hidden;'">
                                Non eiusmod sunt nostrud reprehenderit Lorem. Pariatur et labore sint pariatur laboris. Aliquip anim elit tempor ipsum officia minim anim. Magna nisi deserunt laborum laboris ad exercitation adipisicing elit officia Lorem. Incididunt est elit anim tempor eu ea amet anim excepteur culpa nisi cupidatat. Officia deserunt nisi laboris enim incididunt.
                            </p>
                            <a href="javascript:void(0);" 
                            class="show-more-link text-primary text-decoration-none"
                            style="font-size: 0.9em;"
                            x-text="open ? 'Sembunyikan' : 'Selengkapnya'"
                            x-on:click="open = !open">Selengkapnya</a>                  
                        </div>
                    </li>
                </ul>
            {{-- @else
                <div class="card-body text-center">
                    {{ __('label.datatable_not_found') }}
                </div>
            @endif --}}
        @endif
    </div>
</div>

@script
    <script>
        // jquery handler
        $(function() {
            // $wire.on('toPageResidentJs', (event) => {
            //     $wire.set('isLoading', true).then(() => {
            //         $wire.dispatch('loadDataResidents', { page: event.page });
            //     });
            // });
            // initShowMore('.show-more-container', 60, 'Selengkapnya', 'Sembunyikan');
        });

        $("#btn-filter-collapse").on("click", () => {
            $("#filter-collapse").collapse("toggle");
        });

        $(".btn-page").on("click", (e) => {
            let page = $(e.currentTarget).data("page");
            $wire.set('isLoading', true).then(() => {
                $wire.dispatch('loadDataResidents', { page: page });
            });
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
