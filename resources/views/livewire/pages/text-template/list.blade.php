<?php

use App\Dto\ListDto\ListFilterDto;
use App\Services\TextTemplateService;

use function Livewire\Volt\{state, on, action};

state([
    'isLoading' => true,

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
            'transactionType' => '',
        ],
        'data' => [],
        'total' => 0,
    ],

    'page' => 0,
    'isFilter' => false,
]);

on(['loadDataTextTemplates', 'deleteTextTemplate']);

$loadDataTextTemplates = action(function (?int $page = null, ?bool $clearFilter = null) {
    if ($page != null) {
        $this->list->npage->current = $page;
        $this->list->npage->prev = $page - 1 == 0 ? 0 : $page - 1;
        $this->list->npage->next = $page + 1;
    }

    if ($clearFilter) {
        $this->list->search->general = '';
        $this->isFilter = false;
    }

    if ($page != 0 || $page <= $this->list->npage->max) {
        $this->load();
    }
});

$deleteTextTemplate = action(function (string $id) {
    try {
        $service = app(TextTemplateService::class);
        $service->delete($id);

        $this->isLoading = true;

        $this->dispatch('textTemplateDeletedJs', isSuccess: true);

        $this->dispatch('loadDataTextTemplates', page: 1);
    } catch (\Exception $e) {
        $this->dispatch('textTemplateDeletedJs', isSuccess: false, message: $e->getMessage());
    }
});

$load = function () {
    info('load data text templates');
    $this->isLoading = true;

    // sleep(2);
    $filter = ListFilterDto::fromState($this->list);

    $service = app(TextTemplateService::class);

    $collection = $service->list($filter);

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
                    class="d-none d-md-inline-block btn-icon btn btn-success btn-add"
                    data-refresh="true">
                    <i class="pe-7s-plus btn-icon-wrapper"></i>
                    {{ __('label.add') }}
                </button>

                <button
                    type="button"
                    class="d-inline-block d-md-none btn-icon btn-icon-only btn btn-success btn-add"
                    data-refresh="true">
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
                <div class="col-12 mb-2">
                    <livewire:components.widget.list-transaction-type :id="'search-type'" :class="'select2'" :withModel="false" />
                </div>
                <div class="col-12">
                    <button id="btn-search" class="mb-2 mr-2 btn btn-dark w-100">
                        {{ __('label.search') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div wire:init="loadDataTextTemplates" class="mb-3 card">
        @if($isLoading)
            <x-loading :fullscreen="false" class="p-3" />
        @else
            @if (count($list->data) > 0)
                <ul class="list-group list-group-flush">
                    @foreach ($list->data as $item)
                        <li class="list-group-item px-3">
                            <div class="d-flex">
                                <div class="text-left w-100 align-self-center">
                                    <p class="h6 text-dark my-0 mb-1">
                                        {{ $item->name }}
                                    </p>
                                    <div class="badge badge-focus fs-9">
                                        {{ $item->transactionType->name }}
                                    </div>
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
        $(function () { });

        $("#btn-filter-collapse").on("click", () => {
            $("#filter-collapse").collapse("toggle");
        });

        $(".btn-add").on("click", () => {
            $('#modal-text-template').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            window.dispatchEvent(
                new CustomEvent(
                    'fetchModalTextTemplateContentJs',
                    { detail: { type: 'add' } }
                ));
        });

        $("#btn-search").on("click", () => {
            let search = $("#search").val();
            let searchType = $("#search-type").val();
            $wire.set('isLoading', true).then(() => {
                if (search != "" || searchType != "") {
                    $wire.set('isFilter', true);
                } else {
                    $wire.set('isFilter', false);
                }
                $wire.set('list.search.general', search);
                $wire.set('list.search.transactionType', searchType);
                $wire.dispatch('loadDataTextTemplates', { page: 1});
            });
        });

        $(document).on("click", ".btn-page", function (e) {
            // let page = $(e.currentTarget).data("page");
            let page = $(this).attr("data-page"); // ambil langsung dari attribute, bukan dari cache jQuery
            console.log("page: ", page);
            $wire.set('isLoading', true).then(() => {
                $wire.dispatch('loadDataTextTemplates', { page: page });
            });
        });

        $(document).on("click", ".btn-act", (e) => {
            let $btn = $(e.currentTarget);
            $btn.dropdown("show");
        });

        $(document).on("click", ".btn-edit", (e) => {
            let $btn = $(e.currentTarget);
            let id = $btn.data("id");

            $('#modal-text-template').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            window.dispatchEvent(
                new CustomEvent(
                    'fetchModalTextTemplateContentJs',
                    { detail: { type: 'edit', id: id } }
                ));
        })

        $(document).on("click", ".btn-delete", (e) => {
            let $btn = $(e.currentTarget);
            let id = $btn.data("id");

            showConfirmAlert({
                title: "{{  __('label.alert_title_delete') }}",
                text: "{{  __('text-template.alert_text_delete') }}",
                confirmButtonText: "{{ __('label.button_delete_confirm') }}",
                cancelButtonText: "{{ __('label.button_cancel') }}",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        $wire.dispatch('deleteTextTemplate', { id: id });
                        window.addEventListener('textTemplateDeletedJs', function handler(e) {
                            resolve({ isSuccess: e.detail.isSuccess, message: e.detail.message ?? "" });
                            window.removeEventListener('textTemplateDeletedJs', handler);
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

        // Javascript hanlder
        window.addEventListener("hideModalTextTemplateJs", function (e) {
            $wire.set('isLoading', true).then(() => {
                $("#modal-text-template").modal("hide");
                $("#search").val("");

                $wire.dispatch('loadDataTextTemplates', { page: 1, clearFilter: true });
            });
        });
    </script>
@endscript
