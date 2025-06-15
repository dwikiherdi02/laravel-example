<?php

use App\Enum\TransactionTypeEnum;
use App\Enum\TransactionStatusEnum;
use App\Dto\ListDto\ListFilterDto;
use App\Services\TransactionService;
use Carbon\Carbon;

use function Livewire\Volt\{state, mount, on, action};

state([
    'isLoading' => true,

    'transactionDate' => null,

    'list' => (object) [
        'perpage' => env('DEFAULT_PER_PAGE', 10),
        'npage' => (object) [
            'prev' => 0,
            'current' => 1,
            'next' => 2,
            'max' => 0,
        ],
        'search' => (object) [
            'transactionDate' => null,
            'general' => '',
            'transactionType' => null,
            'transactionMethod' => null,
            'transactionStatus' => null,

        ],
        'data' => [],
        'total' => 0,
    ],

    'page' => 0,
    'isFilter' => false,
]);

mount(function () {
    $now = Carbon::now();
    $this->list->transactionDate = $now->format('d-m-Y');
});

on(['loadDataTransactionDuesHistories', 'loadFilterTransactionDuesHistories']);

$loadDataTransactionDuesHistories = action(function (?int $page = null, ?bool $clearFilter = null) {
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

$loadFilterTransactionDuesHistories = action(function (?string $trdate = null, ?string $date = null, ?string $general = null, ?string $type = null, ?string $method = null, ?string $status = null) {
    $page = 1;

    $this->list->npage->current = $page;
    $this->list->npage->prev = $page - 1 == 0 ? 0 : $page - 1;
    $this->list->npage->next = $page + 1;

    $now = Carbon::now();

    $this->transactionDate = $trdate ?? $now->format('d-m-Y');

    $this->list->search->transactionDate = $date ?? $now->format('Y-m-d');
    $this->list->search->general = $general;
    $this->list->search->transactionType = $type;
    $this->list->search->transactionMethod = $method;
    $this->list->search->transactionStatus = $status;

    $this->isFilter = !empty($date) || !empty($general) || !empty($type) || !empty($method) || !empty($status) ? true : false;

    $this->load();
});

$load = function () {
    info('load data transaction histories');
    $this->isLoading = true;

    $filter = ListFilterDto::fromState($this->list);

    $service = app(TransactionService::class);

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
            </div>
            <div class="float-right">
                <small class="font-weight-bold text-primary">{{ $page }} / {{ $list->total }}</small>
                <div class="btn-group" role="group" aria-label="pagination">
                    <button type="button" class="btn-page btn-icon btn-icon-only btn btn-link"
                        data-page="{{ $list->npage->prev }}" @if ($list->npage->prev == 0) disabled @endif>
                        <i class="fa fa-angle-left btn-icon-wrapper"></i>
                    </button>
                    <button type="button" class="btn-page btn-icon btn-icon-only btn btn-link"
                        data-page="{{ $list->npage->next }}" @if ($list->npage->next > $list->npage->max) disabled
                        @endif>
                        <i class="fa fa-angle-right btn-icon-wrapper"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body collapse @if($isFilter) show @endif" id="filter-collapse">
            <div class="row">
                <div class="col-12 mb-2">
                    <x-text-input id="search-date" type="text" value="{{ $list->transactionDate }}"
                        placeholder="{{  __('label.search_date_placeholder') }}" />
                </div>
                <div class="col-12 mb-2">
                    <x-text-input id="search" type="text" value="{{ $list->search->general }}"
                        placeholder="{{  __('label.search_placeholder') }}" />
                </div>
                <div class="col-12 mb-2">
                    <livewire:components.widget.list-transaction-type :id="'search-type'" :class="'select2'"
                        :withModel="false" />
                </div>
                <div class="col-12 mb-2">
                    <livewire:components.widget.list-transaction-method :id="'search-method'" :class="'select2'"
                        :withModel="false" />
                </div>
                <div class="col-12 mb-2">
                    <livewire:components.widget.list-transaction-status :id="'search-status'" :class="'select2'"
                        :withModel="false" />
                </div>
                <div class="col-12">
                    <button id="btn-search" class="mb-2 mr-2 btn btn-dark w-100">
                        {{ __('label.search') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div wire:init="loadDataTransactionDuesHistories" class="mb-3 card">
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
                                        <p class="fs-6 w-50 text-dark text-left text-truncate font-weight-bold my-0">
                                            {{ $item->name }}
                                        </p>
                                        <p class="fs-7 w-50 text-dark text-right my-0">
                                            {{ format_date($item->date, 'Y M d H:i') }}
                                        </p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between align-items-center">
                                        <div>
                                            {{-- <span class="badge badge-{{ $item->is_paid ? 'success' : 'danger' }}">{{
                                                $item->is_paid ? __('Sudah dibayar') : __('Belum dibayar') }}</span>
                                            @if ($item->is_merge != IsMergeEnum::NoMerge)
                                            <span class="badge badge-info">{{ __('Gabungan') }}</span>
                                            @endif --}}

                                            @php
            $badgeType = 'primary';
            switch ($item->transaction_status_id) {
                case TransactionStatusEnum::Pending:
                    $badgeType = 'secondary';
                    break;


                case TransactionStatusEnum::Success:
                    $badgeType = 'success';
                    break;

                case TransactionStatusEnum::Failed:
                    $badgeType = 'danger';
                    break;
            }
                                            @endphp

                                            <span class="badge badge-{{ $badgeType }}">{{ $item->status->name }}</span>
                                            <span class="badge badge-dark">{{ $item->type->code }}</span>

                                        </div>
                                        <div class="text-right">
                                            <div
                                                class="fs-5 {{ $item->transaction_type_id == TransactionTypeEnum::Credit ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                <span class="fs-7 opacity-7 font-weight-normal">Rp</span>
                                                {{ number_format($item->final_amount, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right align-self-start">
                                    <div class="d-inline-block dropdown">
                                        <button wire:ignore.self type="button" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"
                                            class="border-0 btn-transition btn btn-sm btn-link btn-act p-0 d-none">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <button wire:ignore.self type="button" tabindex="0" class="dropdown-item btn-detail"
                                                data-id="{{ $item->id }}">
                                                <i class="dropdown-icon lnr-eye"></i><span>{{ __('Lihat Detail') }}</span>
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
    $(function () {
        const now = new Date();
        const minDate = new Date(now.getFullYear(), now.getMonth(), 1);

        $("#search-date").datepicker({
            autoHide: true,
            // autoPick: true,
            language: 'id-ID',
        });
    });

    $("#btn-filter-collapse").on("click", () => {
        $("#filter-collapse").collapse("toggle");
    });

    $("#btn-search").on("click", () => {
        let search = $("#search").val();
        let date = $("#search-date").val();
        let type = $("#search-type").val();
        let method = $("#search-method").val();
        let status = $("#search-status").val();

        let sdate = date.replace(/(\d{2})-(\d{2})-(\d{4})/, "$3-$2-$1");

        $wire.set('isLoading', true).then(() => {
            $wire.dispatch('loadFilterTransactionDuesHistories', { trdate: date, date: sdate, general: search, type: type, method: method, status: status });

            // $wire.set('isLoading', true).then(() => {
            //     $wire.set('transactionDate', date);

            //     $wire.set('list.search.general', search);
            //     $wire.set('list.search.transactionDate', sdate);
            //     $wire.set('list.search.transactionType', type);
            //     $wire.set('list.search.transactionMethod', method);
            //     $wire.set('list.search.transactionStatus', status);

            //     setTimeout(() => {
            //         $wire.dispatch('loadDataTransactionDuesHistories', { page: 1 });
            //     }, 500);
            // });
        });
    });

    $(document).on("click", ".btn-page", function (e) {
        // let page = $(e.currentTarget).data("page");
        let page = $(this).attr("data-page"); // ambil langsung dari attribute, bukan dari cache jQuery
        console.log("page: ", page);
        $wire.set('isLoading', true).then(() => {
            $wire.dispatch('loadDataTransactionDuesHistories', { page: page });
        });
    });

    $(document).on("click", ".btn-act", (e) => {
        let $btn = $(e.currentTarget);
        $btn.dropdown("show");
    });

    // Javascript hanlder
    window.addEventListener("hideModalTransactionHistoryJs", function (e) {
        $wire.set('isLoading', true).then(() => {
            $("#modal-transaction-history").modal("hide");
            $("#search").val("");
            $("#is-paid").val("");

            $wire.dispatch('loadDataTransactionDuesHistories', { page: 1, clearFilter: true });
        });
    });
</script>
@endscript
