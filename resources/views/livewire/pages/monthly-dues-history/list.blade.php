<?php

use App\Enum\IsMergeEnum;
use App\Enum\RoleEnum;
use App\Dto\ListDto\ListFilterDto;
use App\Services\DuesPaymentService;
use Carbon\Carbon;

use function Livewire\Volt\{state, mount, on, action};

state('year')->url(as: 'year', keep: true);
state('month')->url(as: 'month', keep: true);

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
            'dues_date' => null,
            'year' => null,
            'month' => null,
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
    } else {
        $now = Carbon::now();
        $this->list->search->dues_date = $now->format('m-Y');
        $this->list->search->year = $now->year;
        $this->list->search->month = $now->month;

        $this->year = $now->format('Y');
        $this->month = $now->format('m');
    }
});

on(['loadDataMonthlyDuesHistories']);

$loadDataMonthlyDuesHistories = action(function (?int $page = null, ?bool $clearFilter = null) {
    if ($page != null) {
        $this->list->npage->current = $page;
        $this->list->npage->prev = $page - 1 == 0 ? 0 : $page - 1;
        $this->list->npage->next = $page + 1;
    }

    if ($clearFilter) {
        $this->list->search->general = null;
        $this->list->search->isPaid = null;
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

                <button
                    type="button"
                    class="d-none d-md-inline-block btn-icon btn btn-success btn-house-bill-merge"
                    data-refresh="true">
                    <i class="pe-7s-albums btn-icon-wrapper"></i>
                    {{ __('Gabung Tagihan Rumah') }}
                </button>

                <button
                    type="button"
                    class="d-inline-block d-md-none btn-icon btn-icon-only btn btn-success btn-house-bill-merge"
                    data-refresh="true">
                    <i class="pe-7s-albums btn-icon-wrapper"> </i>
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
                <div class="col-12 mb-2">
                    <x-select-list :id="'is-paid'">
                        <option value="">{{ __('Pilih Status') }}</option>
                        <option value="0">{{ __('Belum Dibayar') }}</option>
                        <option value="1">{{ __('Sudah Dibayar') }}</option>
                    </x-select-list>
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
                                        @switch($item->is_merge)
                                            @case(IsMergeEnum::HouseBillMerge)
                                                <p class="fs-6 w-75 text-dark text-left text-truncate font-weight-bold my-0">
                                                    {{ $item->childs->pluck('resident.housing_block')->implode(', ') }}
                                                </p>
                                                <p class="fs-7 w-25 text-dark text-right my-0">
                                                    {{ format_month_year($item->duesMonth->month, $item->duesMonth->year) }}
                                                </p>
                                                @break

                                            @case(IsMergeEnum::MonthlyBillMerge)
                                                <p class="fs-6 w-50 text-dark text-left text-truncate font-weight-bold my-0">
                                                    {{ $item->resident->housing_block }}
                                                </p>
                                                <p class="fs-7 w-50 text-dark text-right my-0">
                                                    @php
                                                        $sortedBills = $item->childs->pluck('duesMonth')->sortBy(function($duesMonth) {
                                                            return sprintf('%04d-%02d', $duesMonth->year, $duesMonth->month);
                                                        });
                                                        $firstBill = $sortedBills->first();
                                                        $lastBill = $sortedBills->last();
                                                    @endphp
                                                    {{ format_month_year($firstBill->month, $firstBill->year) }}
                                                    @if($firstBill != $lastBill)
                                                        s/d {{ format_month_year($lastBill->month, $lastBill->year) }}
                                                    @endif
                                                </p>
                                                @break
                                        
                                            @default
                                                <p class="fs-6 w-75 text-dark text-left text-truncate font-weight-bold my-0">
                                                    {{ $item->resident->housing_block }}
                                                </p>
                                                <p class="fs-7 w-25 text-dark text-right my-0">
                                                    {{ format_month_year($item->duesMonth->month, $item->duesMonth->year) }}
                                                </p>
                                        @endswitch
                                    </div>
                                    <div class="d-flex flex-row justify-content-between align-items-center">
                                        <div>
                                            <span class="badge badge-{{ $item->is_paid ? 'success' : 'danger' }}">{{ $item->is_paid ? __('Sudah dibayar') : __('Belum dibayar') }}</span>
                                            @if ($item->is_merge != IsMergeEnum::NoMerge)
                                                <span class="badge badge-info">{{ __('Gabungan') }}</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <div class="fs-5 text-success font-weight-bold">
                                                <span class="fs-7 opacity-7 font-weight-normal">Rp</span>
                                                {{ number_format($item->final_amount, 0, ',', '.') }}
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
                                            <button wire:ignore.self type="button" tabindex="0" class="dropdown-item btn-detail" data-id="{{ $item->id }}">
                                                <i class="dropdown-icon lnr-eye"></i><span>{{ __('Lihat Detail') }}</span>
                                            </button>
                                            @if (auth_role() != RoleEnum::Warga)    
                                                @if ($item->is_merge == IsMergeEnum::NoMerge)
                                                <div tabindex="-1" class="dropdown-divider"></div>
                                                <button wire:ignore.self type="button" tabindex="0" class="dropdown-item btn-monthly-bill-merge" data-resident-id="{{ $item->resident_id }}">
                                                    <i class="dropdown-icon lnr-layers"></i><span>{{ __('Gabung Tagihan Bulanan') }}</span>
                                                </button>
                                                @endif

                                                @if ($item->is_paid == false)
                                                <div tabindex="-1" class="dropdown-divider"></div>
                                                <button wire:ignore.self type="button" tabindex="0" class="dropdown-item btn-mark-as-paid" data-id="{{ $item->id }}">
                                                    <i class="dropdown-icon lnr-tag"></i><span>{{ __('Tandai Sudah Bayar') }}</span>
                                                </button>
                                                @endif
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

        $(".btn-house-bill-merge").on("click", () => {
            $('#modal-dues-history').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            let duesDate = $("#dues-date").val();
            let [month, year] = duesDate.split('-');

            window.dispatchEvent(
                new CustomEvent(
                    'fetchModalDuesHistoryContentJs',
                    { detail: {
                        type: 'houes-bill-merge',
                        year: parseInt(year),
                        month: parseInt(month),
                    } }
                ));
        });

        $("#btn-search").on("click", () => {
            let search = $("#search").val();
            let duesDate = $("#dues-date").val();
            let [month, year] = duesDate.split('-');
            let isPaid = $("#is-paid").val();
            if (isPaid == "") {
                isPaid = null;
            } else {
                isPaid = parseInt(isPaid);
            }

            $wire.set('isLoading', true).then(() => {
                if (search != "" || duesDate != "") {
                    $wire.set('isFilter', true);
                } else {
                    $wire.set('isFilter', false);
                }
                $wire.set('year', year);
                $wire.set('month', month);

                $wire.set('list.search.general', search);
                $wire.set('list.search.dues_date', duesDate);
                $wire.set('list.search.year', parseInt(year));
                $wire.set('list.search.month', parseInt(month));
                $wire.set('list.search.isPaid', isPaid);
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

        $(document).on("click", ".btn-detail", (e) => {
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
                    { detail: { type: 'detail', id: id } }
                ));
        });

        $(document).on("click", ".btn-monthly-bill-merge", (e) => {
            let $btn = $(e.currentTarget);
            let resident_id = $btn.data("resident-id");            

            $('#modal-dues-history').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            window.dispatchEvent(
                new CustomEvent(
                    'fetchModalDuesHistoryContentJs',
                    { detail: { type: 'monthly-bill-merge', resident_id: resident_id } }
                ));
        });

        $(document).on("click", ".btn-mark-as-paid", (e) => {
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
                    { detail: { type: 'mark-as-paid', id: id } }
                ));
        });
        
        // Javascript hanlder
        window.addEventListener("hideModalDuesHistoryJs", function (e) {
            $wire.set('isLoading', true).then(() => {
                $("#modal-dues-history").modal("hide");
                $("#search").val("");
                $("#is-paid").val("");

                $wire.dispatch('loadDataMonthlyDuesHistories', { page: 1, clearFilter: true });
            });
        });
    </script>
@endscript
