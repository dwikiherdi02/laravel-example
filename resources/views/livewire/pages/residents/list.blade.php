<?php

use App\Dto\ListDto\ListFilterDto;
use App\Services\ResidentService;

use function Livewire\Volt\{state, on, action};

state([
    'isLoading' => true,
    // 'device' => 'desktop',

    'list' => (object) [
        'perpage' => env('DEFAULT_PER_PAGE', 10),
        'npage' => (object) [
            'prev' => 0,
            'current' => 1,
            'next' => 2,
            'max' => 0,
        ],
        'search' => (object) [
            'general' => '',
        ],
        'data' => [],
        'total' => 0,
    ],

    'page' => 0,
    'isFilter' => false,
]);

on(['loadDataResidents', 'toPageResident', 'deleteResident']);

$loadDataResidents = action(function ($page = null) {
    if ($page != null) {
        $this->list->npage->current = $page;
        $this->list->npage->prev = $page - 1 == 0 ? 0 : $page - 1;
        $this->list->npage->next = $page + 1;
    }

    if ($page != 0 || $page <= $this->list->npage->max) {
        $this->load();
    }
});

$toPageResident = action(function ($page = null) {
    $this->isLoading = true;
    $this->dispatch('loadDataResidents', page: $page);
});

$deleteResident = action(function ($id) {
    try {
        $service = app(ResidentService::class);
        $service->delete($id);

        $this->isLoading = true;

        $this->dispatch('residentDeletedJs', isSuccess: true);

        $this->dispatch('loadDataResidents', page: 1);
    } catch (\Exception $e) {
        $this->dispatch('residentDeletedJs', isSuccess: false, message: $e->getMessage());
    }
});

$load = function () {
    info('load data residents from load function');
    $this->isLoading = true;

    // sleep(2);
    $filter = ListFilterDto::fromState($this->list);

    $service = app(ResidentService::class);

    $collection = $service->list($filter);

    // dd($collection->data);

    $this->list->data = $collection->data;
    $this->list->total = $collection->total;
    $this->list->npage->max = ceil($this->list->total / $this->list->perpage);

    $this->isLoading = false;
    $this->generatePage();
};

$generatePage = function () {
    if ($this->list->total == 0) {
        $this->page = 0;
        return;
    }

    $start = $this->list->npage->current * $this->list->perpage - $this->list->perpage + 1;
    $end = $this->list->npage->current * $this->list->perpage;
    if ($end > $this->list->total) {
        $end = $this->list->total;
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
                <small class="font-weight-bold text-primary">{{ $page }} / {{ $list->total }}</small>
                <div class="btn-group" role="group" aria-label="pagination">
                    <button
                        type="button"
                        class="btn-page btn-icon btn-icon-only btn btn-link"
                        data-page="{{ $list->npage->prev }}"
                        @if ($list->npage->prev == 0) disabled @endif>
                        <i class="fa fa-angle-left btn-icon-wrapper"></i>
                    </button>
                    <button
                        type="button"
                        class="btn-page btn-icon btn-icon-only btn btn-link"
                        data-page="{{ $list->npage->next }}"
                        @if ($list->npage->next > $list->npage->max) disabled @endif>
                        <i class="fa fa-angle-right btn-icon-wrapper"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body collapse @if($isFilter) show @endif" id="filter-collapse">
            <div class="row">
                <div class="col-12 mb-2">
                    <x-text-input
                        id="search"
                        type="text"
                        value="{{ $list->search->general }}"
                        placeholder="{{  __('label.search_placeholder') }}" />
                </div>
                <div class="col-12">
                    <button id="btn-search" class="mb-2 mr-2 btn btn-dark w-100">
                        {{ __('label.search') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div wire:init="loadDataResidents" class="mb-3 card">
        @if($isLoading)
            <x-loading :fullscreen="false" class="p-3" />
        @else
            @if (count($list->data) > 0)
                <ul class="list-group list-group-flush">
                    @foreach ($list->data as $item)
                        <li class="list-group-item px-3">
                            <div class="d-flex justify-content-between">
                                <div class="text-left w-75">
                                    <p class="fs-6 w-75 text-dark text-left text-truncate font-weight-bold my-0">
                                        {{ $item->housing_block }}
                                    </p>
                                </div>
                                <div class="text-right w-25 align-self-start">
                                    <div class="d-inline-block dropdown">
                                        <button
                                            wire:ignore.self
                                            type="button"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                            class="border-0 btn-transition btn btn-sm btn-link btn-act">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <button wire:ignore.self type="button" tabindex="0" class="dropdown-item btn-edit" data-id="{{ $item->id }}">
                                                <i class="dropdown-icon lnr-pencil"></i><span>{{ __('label.edit') }}</span>
                                            </button>
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <button wire:ignore.self type="button" tabindex="0" class="dropdown-item btn-delete" data-id="{{ $item->id }}">
                                                <i class="dropdown-icon lnr-trash"></i><span>{{ __('label.delete') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 0.9em;">
                                {{ $item->name }} | {{ $item->phone_number }} | {{ __('resident.short_label_unique_code') . ': ' . $item->unique_code }}
                            </p>
                            {{-- <div class="show-more-container" x-data="{ open: false }">
                                <p class="text-secondary text-justify my-0 show-more-text" :style="open ? 'max-height:none;overflow:visible;' : 'max-height:20px;overflow:hidden;'">
                                    {{ $item->address }}
                                </p>
                                <a href="javascript:void(0);"
                                class="show-more-link text-primary text-decoration-none"
                                style="font-size: 0.9em;"
                                x-text="open ? '{{ __('label.show_less') }}' : '{{ __('label.show_more') }}'"
                                x-on:click="open = !open">{{ __('label.show_more') }}</a>
                            </div> --}}
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="card-body text-center">
                    {{ __('label.data_not_found') }}
                </div>
            @endif
        @endif
    </div>
</div>

@script
    <script>
        // jquery handler
        $("#btn-filter-collapse").on("click", () => {
            $("#filter-collapse").collapse("toggle");
        });

        $(document).on("click", ".btn-page", (e) => {
            // let page = $(e.currentTarget).data("page");
            let page = $(e.currentTarget).attr("data-page");
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

        $("#btn-search").on("click", () => {
            let search = $("#search").val();
            $wire.set('isLoading', true).then(() => {
                if (search != "") {
                    $wire.set('isFilter', true);
                } else {
                    $wire.set('isFilter', false);
                }
                $wire.set('list.search.general', search);
                $wire.dispatch('loadDataResidents');
            });
        });

        $(document).on("click", ".btn-act", (e) => {
            let $btn = $(e.currentTarget);
            $btn.dropdown("show");
        });

        $(document).on("click", ".btn-edit", (e) => {
            let $btn = $(e.currentTarget);
            $(".btn-act").dropdown("hide");
            let id = $btn.data("id");
            $btn.prop("disabled", true);
            $wire.dispatch('openModalResident', { type: 'edit', id: id });
            // Enable tombol setelah event custom dari modal
            window.addEventListener('residentModalOpened', function handler() {
                $btn.prop("disabled", false);
                window.removeEventListener('residentModalOpened', handler);
            });
        });

        $(document).on("click", ".btn-delete", (e) => {
            let $btn = $(e.currentTarget);
            let id = $btn.data("id");

            showConfirmAlert({
                title: "{{  __('label.alert_title_delete') }}",
                text: "{{ __('resident.alert_text_delete') }}",
                // text: "Yakin ingin menghapus data ini?",
                confirmButtonText: "{{ __('label.button_delete_confirm') }}",
                cancelButtonText: "{{ __('label.button_cancel') }}",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        $wire.dispatch('deleteResident', { id: id });
                        window.addEventListener('residentDeletedJs', function handler(e) {
                            resolve({ isSuccess: e.detail.isSuccess, message: e.detail.message ?? "" });
                            window.removeEventListener('residentDeletedJs', handler);
                        });
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && !result.value.isSuccess) {
                    showInfoAlert({
                        text: result.value.message,
                        confirmButtonText: "{{ __('label.button_ok') }}",
                    });
                }
            });

        });

        // Javascript handler
        window.addEventListener('reloadDataResident', function handler() {
            $wire.set('isLoading', true).then(() => {
                $wire.dispatch('loadDataResidents', { page: 1 });
                // window.removeEventListener('reloadDataResident', handler);
            });
        });
    </script>
@endscript
