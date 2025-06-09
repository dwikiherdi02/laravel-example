<?php

use App\Dto\ListDto\ListFilterDto;
use App\Services\DuesPaymentService;
use Carbon\Carbon;

use function Livewire\Volt\{state, mount, on, action};

state('year')->url(as: 'year');
state('month')->url(as: 'month');

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
            'dues_date' => Carbon::now()->format('m-Y'),
            'year' => Carbon::now()->year,
            'month' => Carbon::now()->month,
            'general' => '',
            'isPaid' => null,

        ],
        'data' => [],
        'total' => 0,
    ],

    'page' => 0,
    'isFilter' => false,
]);

mount(function () {
    if ($this->year != null && $this->month != null) {
        $this->list->search->dues_date = $this->month . '-' . $this->year;
        $this->list->search->year = (int) $this->year;
        $this->list->search->month = (int) $this->month;
    }
});

on(['loadDataMonthlyDuesHistories']);

$loadDataMonthlyDuesHistories = action(function (?int $page = null, ?bool $clearFilter = null) {
    if ($page != null) {
        $this->list->npage->current = $page;
        $this->list->npage->prev = $page - 1 == 0 ? 0 : $page - 1;
        $this->list->npage->next = $page + 1;
    }

    if($clearFilter) {
        $this->list->search->general = '';
        $this->isFilter = false;
    }

    if ($page != 0 || $page <= $this->list->npage->max) {
        $this->load();
    }
});

$load = function () {
    info('load data monthly dues histories');
    $this->isLoading = true;

    $filter = ListFilterDto::fromState($this->list);

    $service = app(DuesPaymentService::class);

    $collection = $service->list($filter);

    // dd($collection->data->toArray());

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
                        id="dues-date" 
                        type="text" 
                        value="{{ $list->search->dues_date }}"
                        placeholder="{{  __('label.search_date_placeholder') }}" />
                </div>
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

    <div wire:init="loadDataMonthlyDuesHistories" class="mb-3 card">
        @if($isLoading)
            <x-loading :fullscreen="false" class="p-3" />
        @else
            @if (count($list->data) > 0)
                <ul class="list-group list-group-flush">
                    @foreach ($list->data as $item)
                        <li class="list-group-item px-3">
                            <div class="d-flex flex-row justify-content-between align-items-center">
                                <div class="w-100 d-flex flex-column pr-3">
                                    <div class="d-flex flex-row justify-content-between align-items-center">
                                        <p class="fs-6 w-75 text-dark text-left text-truncate font-weight-bold my-0">
                                            {{ $item->resident->housing_block }}
                                        </p>
                                        <p class="fs-7 w-25 text-dark text-right my-0">
                                            {{ format_month_year($item->duesMonth->month, $item->duesMonth->year) }}
                                        </p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between align-items-center">
                                        <div>
                                            <span class="badge badge-{{ $item->is_paid ? 'success' : 'secondary' }}">{{ $item->is_paid ? __('Sudah dibayar') : __('Belum dibayar') }}</span>
                                            @if ($item->is_merge)
                                                <span class="badge badge-info">{{ __('Digabung') }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fs-5 text-success font-weight-bold">
                                                <span class="fs-7 opacity-7 font-weight-normal">Rp</span> {{ number_format($item->final_amount, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right align-self-start">
                                    <div class="d-inline-block dropdown">
                                        <button
                                            wire:ignore.self
                                            type="button"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                            class="border-0 btn-transition btn btn-sm btn-link btn-act p-0">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            {{-- ... --}}
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
        });

        $("#btn-filter-collapse").on("click", () => {
            $("#filter-collapse").collapse("toggle");
        });

        $(".btn-add").on("click", () => {
            $('#modal-dues-history').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            window.dispatchEvent(
                new CustomEvent(
                    'fetchModalDuesHistoryContentJs',
                    { detail: { type: 'add' } }
                ));
        });

        $("#btn-search").on("click", () => {
            let search = $("#search").val();
            let duesDate = $("#dues-date").val();
            let [month, year] = duesDate.split('-');
            month = parseInt(month);
            year = parseInt(year);

            $wire.set('isLoading', true).then(() => {
                if (search != "" || duesDate != "") {
                    $wire.set('isFilter', true);
                } else {
                    $wire.set('isFilter', false);
                }
                $wire.set('list.search.general', search);
                $wire.set('list.search.dues_date', duesDate);
                $wire.set('list.search.year', year);
                $wire.set('list.search.month', month);
                $wire.dispatch('loadDataMonthlyDuesHistories', { page: 1});
            });
        });

        $(document).on("click", ".btn-page", function (e) {
            // let page = $(e.currentTarget).data("page");
            let page = $(this).attr("data-page"); // ambil langsung dari attribute, bukan dari cache jQuery
            console.log("page: ", page);
            $wire.set('isLoading', true).then(() => {
                $wire.dispatch('loadDataMonthlyDuesHistories', { page: page });
            });
        });

        $(document).on("click", ".btn-act", (e) => {
            let $btn = $(e.currentTarget);
            $btn.dropdown("show");
        });

        $(document).on("click", ".btn-edit", (e) => {
            let $btn = $(e.currentTarget);
            let id = $btn.data("id");

            $('#modal-dues-history').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            window.dispatchEvent(
                new CustomEvent(
                    'fetchModalDuesHistoryContentJs',
                    { detail: { type: 'edit', id: id } }
                ));
        });

        // Javascript hanlder
        window.addEventListener("hideModalDuesHistoryJs", function (e) {
            $wire.set('isLoading', true).then(() => {
                $("#modal-dues-history").modal("hide");
                $("#search").val("");

                $wire.dispatch('loadDataMonthlyDuesHistories', { page: 1, clearFilter: true });
            });
        });
    </script>
@endscript
