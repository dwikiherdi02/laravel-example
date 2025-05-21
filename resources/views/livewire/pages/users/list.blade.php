<?php

use App\Dto\ListDto\ListFilterDto;
use App\Facades\Avatar;
use App\Services\UserService;

use function Livewire\Volt\{state, on, action};

state([
    'isLoading' => false,

    'list' => (object) [
        'perpage' => 10,
        'npage' => (object) [
            'prev' => 0,
            'current' => 1,
            'next' => 2,
            'max' => 0,
        ],
        'search' => (object) [
            'general' => '',
            'role' => '',
        ],
        'data' => [],
        'total' => 0,
    ],

    'page' => 0,
    'isFilter' => false,
]);

on(['loadDataUsers', 'toPageUser', 'deleteUser', 'resetUserPassword']);

$loadDataUsers = action(function (?int $page = null) {
    if ($page != null) {
        $this->list->npage->current = $page;
        $this->list->npage->prev = $page - 1 == 0 ? 0 : $page - 1;
        $this->list->npage->next = $page + 1;
    }

    if ($page != 0 || $page <= $this->list->npage->max) {
        $this->load();
    }
});

$toPageUser = action(function (?int $page = null) {
    $this->isLoading = true;
    $this->dispatch('loadDataUsers', page: $page);
});

$deleteUser = action(function (string $id) {
    try {
        $service = app(UserService::class);
        $service->delete($id);

        $this->isLoading = true;

        $this->dispatch('userDeletedJs', isSuccess: true);

        $this->dispatch('loadDataUsers', page: 1);
    } catch (\Exception $e) {
        $this->dispatch('userDeletedJs', isSuccess: false, message: $e->getMessage());
    }
});

$resetUserPassword = action(function (string $id) {
    try {
        $service = app(UserService::class);
        $message = $service->resetPassword($id);

        $this->dispatch('userPasswordResetJs', isSuccess: true, message: $message);

        $this->dispatch('loadDataUsers', page: 1);
    } catch (\Exception $e) {
        $this->dispatch('userPasswordResetJs', isSuccess: false, message: $e->getMessage());
    }
});

$load = function () {
    info('load data residents from load function');
    $this->isLoading = true;

    // sleep(2);
    $filter = ListFilterDto::fromState($this->list);

    $service = app(UserService::class);

    $collection = $service->list($filter);

    // dd($collection->data);

    $collection->data = $collection->data->map(function ($item) {
        $item->avatar = Avatar::create($item->name)->toBase64();
        return $item;
    });

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

                {{-- <button
                    type="button"
                    class="d-none d-md-inline-block btn-icon btn btn-success btn-add">
                    <i class="pe-7s-plus btn-icon-wrapper"></i>
                    {{ __('label.add') }}
                </button>

                <button
                    type="button"
                    class="d-inline-block d-md-none btn-icon btn-icon-only btn btn-success btn-add">
                    <i class="pe-7s-plus btn-icon-wrapper"> </i>
                </button> --}}
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

    <div wire:init="loadDataUsers" class="mb-3 card">
        @if($isLoading)
            <x-loading :fullscreen="false" class="p-3" />
        @else
            @if (count($list->data) > 0)
                <ul class="list-group list-group-flush">
                    @foreach ($list->data as $item)
                        <li class="list-group-item px-3">
                            <div class="d-flex">
                                <div class="avatar-icon-wrapper pr-1">
                                    @persist('userAvatar')
                                    <div class="avatar-icon"><img src="{{ $item->avatar }}" alt=""></div>
                                    @endpersist
                                </div>
                                <div class="text-left w-100">
                                    <p class="h6 text-dark my-0">
                                        {{ $item->name }}
                                    </p>
                                    <div class="badge badge-focus fs-9">
                                        {{ $item->role->name }}
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
                                            <button wire:ignore.self type="button" tabindex="0" class="dropdown-item btn-detail" data-id="{{ $item->id }}">
                                                <i class="dropdown-icon lnr-eye"></i><span>{{ __('user.button_detail_user') }}</span>
                                            </button>
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <button wire:ignore.self type="button" tabindex="0" class="dropdown-item btn-reset-password" data-id="{{ $item->id }}">
                                                <i class="dropdown-icon lnr-undo"></i><span>{{ __('user.button_reset_user_password') }}</span>
                                            </button>
                                            @if(!$item->is_protected)
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <button wire:ignore.self type="button" tabindex="0" class="dropdown-item btn-delete" data-id="{{ $item->id }}">
                                                <i class="dropdown-icon lnr-trash"></i><span>{{ __('label.delete') }}</span>
                                            </button>
                                            @endif
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
        $("#btn-filter-collapse").on("click", () => {
            $("#filter-collapse").collapse("toggle");
        });

        $(document).on("click", ".btn-page", function (e) {
            // let page = $(e.currentTarget).data("page");
            let page = $(this).attr("data-page"); // ambil langsung dari attribute, bukan dari cache jQuery
            console.log("page: ", page);
            $wire.set('isLoading', true).then(() => {
                $wire.dispatch('loadDataUsers', { page: page });
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
                $wire.dispatch('loadDataUsers');
            });
        });

        $(document).on("click", ".btn-act", (e) => {
            let $btn = $(e.currentTarget);
            $btn.dropdown("show");
        });

        $(document).on("click", ".btn-delete", (e) => {
            let $btn = $(e.currentTarget);
            let id = $btn.data("id");

            showConfirmAlert({
                title: "{{  __('label.alert_title_delete') }}",
                text: "{{  __('user.alert_text_delete') }}",
                confirmButtonText: "{{ __('label.button_delete_confirm') }}",
                cancelButtonText: "{{ __('label.button_cancel') }}",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        $wire.dispatch('deleteUser', { id: id });
                        window.addEventListener('userDeletedJs', function handler(e) {
                            resolve({ isSuccess: e.detail.isSuccess, message: e.detail.message ?? "" });
                            window.removeEventListener('userDeletedJs', handler);
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

        $(document).on("click", ".btn-reset-password", (e) => {
            let $btn = $(e.currentTarget);
            let id = $btn.data("id");

            showConfirmAlert({
                title: "{{ __('user.alert_title_reset_password') }}",
                text: "{{ __('user.alert_text_reset_password') }}",
                confirmButtonText: "{{ __('user.button_confirm_reset_password') }}",
                cancelButtonText: "{{ __('label.button_cancel') }}",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        $wire.dispatch('resetUserPassword', { id: id });
                        window.addEventListener('userPasswordResetJs', function handler(e) {
                            resolve({ isSuccess: e.detail.isSuccess, message: e.detail.message ?? "" });
                            window.removeEventListener('userPasswordResetJs', handler);
                        });
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value.message != "") {
                    showInfoAlert({
                        html: result.value.message,
                        confirmButtonText: "{{ __('label.button_ok') }}",
                    });
                }
            });
        });
    </script>
@endscript
