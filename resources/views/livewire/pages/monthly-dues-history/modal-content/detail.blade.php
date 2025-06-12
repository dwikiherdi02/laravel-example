<?php

use App\Enum\IsMergeEnum;
use App\Services\DuesPaymentService;

use function Livewire\Volt\{placeholder, state, mount};

placeholder('components.loading');

state([
    'id',
    'item' => null,
]);

mount(function (DuesPaymentService $service) {
    $this->item = $service->findById($this->id);
});

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('Detail Tagihan') }}</h5>
        <button type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        @if($id != null)
        <div class="card bg-transparent border-0 shadow-none">
            <div class="card-header d-flex justify-content-between align-items-center px-0">
                <section class="d-flex flex-row align-items-center">
                    <i class="header-icon fas fa-list icon-gradient bg-happy-itmeo py-1 d-block"> </i> {{ __('Data Tagihan') }}
                </section>
                <section>
                    @if ($item->is_merge != IsMergeEnum::NoMerge)
                        <span class="badge badge-info">{{ __('Gabungan') }}</span>
                    @endif
                    <span class="badge badge-{{ $item->is_paid ? 'success' : 'danger' }}">{{ $item->is_paid ? __('Sudah dibayar') : __('Belum dibayar') }}</span>
                </section>
            </div>
            <div class="card-body px-0">
                <table class="mb-0 table table-borderless">
                    <tbody>
                        @switch($item->is_merge)
                            @case(IsMergeEnum::HouseBillMerge)
                                <livewire:pages.monthly-dues-history.modal-content.detail.house-bill-merge :item="$item" />
                                @break

                            @case(IsMergeEnum::MonthlyBillMerge)
                                <livewire:pages.monthly-dues-history.modal-content.detail.monthly-bill-merge :item="$item" />
                                @break

                            @default
                                <livewire:pages.monthly-dues-history.modal-content.detail.default :item="$item" />
                        @endswitch
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="d-flex justify-content-center align-items-center" style="height: 82vh;">
            <div class="text-center w-100">
                <p class="h6">{{ __('Data tagihan tidak ditemukan. silahkan coba lagi atau hubungi admin.') }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        <button type="button" class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0" data-dismiss="modal">
            {{ __('label.cancel') }}
        </button>
    </div>
</div>

@script
<script>
    $(function () {
        generateScrollbar();
    });
</script>
@endscript
