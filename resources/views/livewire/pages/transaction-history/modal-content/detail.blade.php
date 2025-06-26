<?php

use App\Enum\IsMergeEnum;
use App\Enum\TransactionStatusEnum;
use App\Services\TransactionService;

use function Livewire\Volt\{placeholder, state, mount};

placeholder('components.loading');

state([
    'id',
    'item' => null
]);

mount(function (TransactionService $service) {
    $transaction = $service->findById($this->id, true);
    // dd($transaction);
    if ($transaction) {
        $this->item = $transaction;
    }
});

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('Detail') }}</h5>
        <button type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        @if($id != null && $item != null)
        <div id="accordion" class="accordion-wrapper border-0 mb-3">
            <div class="card">
                <div id="transaction" class="card-header px-0">
                    <button type="button" data-toggle="collapse" data-target="#collapse-transaction" aria-expanded="true" aria-controls="collapseOne" class="text-left m-0 p-0 btn btn-link btn-block d-flex align-items-center fs-6 text-secondary font-weight-bold">
                        <i class="header-icon pe-7s-cash icon-gradient bg-happy-itmeo py-1 d-block"> </i> {{ __('Transaksi') }}
                    </button>
                </div>
                <div data-parent="#accordion" id="collapse-transaction" aria-labelledby="transaction" class="collapse show">
                    <div class="card-body px-0">
                        <label class="font-weight-bold">{{ __('Nama Transaksi') }}</label>
                        <p>{{ $item->name }}</p>

                        <label class="font-weight-bold">{{ __('Nominal') }}</label>
                        <p class="text-success">
                            <span class="fs-7 opacity-7 font-weight-normal">Rp</span>
                            {{ number_format($item->final_amount, 0, ',', '.') }}
                        </p>

                        <label class="font-weight-bold">{{ __('Tipe Transaksi') }}</label>
                        <p>{{ $item->type->name }}</p>

                        <label class="font-weight-bold">{{ __('Metode') }}</label>
                        <p>{{ $item->method->name }}</p>

                        <label class="font-weight-bold">{{ __('Status') }}</label>
                        <p>
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
                        </p>
                    </div>
                </div>
            </div>

            @if ($item->duesPayment != null)
            <div class="card">
                <div id="bill" class="b-radius-0 card-header px-0">
                    <button type="button" data-toggle="collapse" data-target="#collapse-bill" aria-expanded="false" aria-controls="collapseTwo" class="text-left m-0 p-0 btn btn-link btn-block d-flex align-items-center fs-6 text-secondary font-weight-bold">
                        <i class="header-icon pe-7s-calculator icon-gradient bg-happy-itmeo py-1 d-block"> </i> {{ __('Tagihan') }}
                    </button>
                </div>
                <div data-parent="#accordion" id="collapse-bill" aria-labelledby="bill" class="collapse">
                    <div class="card-body px-0">
                        <table class="mb-0 table table-borderless">
                            <tbody>
                                @switch($item->duesPayment->is_merge)
                                    @case(IsMergeEnum::HouseBillMerge)
                                        <livewire:pages.transaction-history.modal-content.detail.house-bill-merge :item="$item->duesPayment" />
                                        @break

                                    @case(IsMergeEnum::MonthlyBillMerge)
                                        <livewire:pages.transaction-history.modal-content.detail.monthly-bill-merge :item="$item->duesPayment" />
                                        @break

                                    @default
                                        <livewire:pages.transaction-history.modal-content.detail.default :item="$item->duesPayment" />
                                @endswitch
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @if ($item->email != null)
            <div class="card">
                <div id="email" class="card-header px-0">
                    <button type="button" data-toggle="collapse" data-target="#collapse-email" aria-expanded="false" aria-controls="collapseThree" class="text-left m-0 p-0 btn btn-link btn-block d-flex align-items-center fs-6 text-secondary font-weight-bold">
                        <i class="header-icon pe-7s-mail icon-gradient bg-happy-itmeo py-1 d-block"> </i> {{ __('Email') }}
                    </button>
                </div>
                <div data-parent="#accordion" id="collapse-email" aria-labelledby="email"  class="collapse">
                    <div class="card-body px-0">
                        {!! $item->email->body_html !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="d-flex justify-content-center align-items-center" style="height: 82vh;">
            <div class="text-center w-100">
                <p class="h6">{{ __('Data transaksi tidak ditemukan. silahkan coba lagi atau hubungi admin.') }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        <button
            type="button"
            class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0"
            data-dismiss="modal">
            {{ __('label.close') }}
        </button>
    </div>
</div>
