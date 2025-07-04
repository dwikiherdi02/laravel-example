<?php

use App\Dto\MonthlyDuesHistoryDto;
use App\Enum\IsMergeEnum;
use App\Services\DuesPaymentService;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{placeholder, state, rules, mount};

placeholder('components.loading');

state([
    // attributes
    'resident_id' => null,
    'items' => null,

    // form
    'dues_payment_ids' => [],

    // alert
    'alertMessage' => '',
    'isError' => false,
]);

rules([
    'dues_payment_ids' => ['required', 'array'],
])->attributes([
    'dues_payment_ids' => __('dues_payment.label_bill_list'),
])->messages([
    'dues_payment_ids.required' => __('label.error_not_selected_item', ['attribute' => __('dues_payment.label_bill_list')]),
]);

mount(function (DuesPaymentService $service) {
    // dd('resident_id: ' . $this->resident_id);
    $items = $service->listMergeByResidentId($this->resident_id);
    $this->items = $items;
});

$createMonthlyBillMerge = function (DuesPaymentService $service) {
    try {
        $this->isError = false;
        $this->alertMessage = null;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = MonthlyDuesHistoryDto::from($validated);

        $data->is_merge = IsMergeEnum::MonthlyBillMerge;

        $service->createMerge($data);

        $this->dispatch('hideModalDuesHistoryJs');
    } catch (ValidationException $e) {
        $this->isError = true;
        $this->alertMessage = $e->getMessage();
        // throw $e;
    } catch (\Exception $e) {
        $this->isError = true;
        $this->alertMessage = $e->getMessage();
        // $this->reset('dues_date', 'contribution_ids');
    }
};

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('dues_payment.label_house_bill_merge') }}</h5>
        <button wire:target="createMonthlyBillMerge" wire:loading.attr="disabled" type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        @if ($items->count() > 0)
        <div class="alert @if($isError) alert-danger @else alert-success @endif alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
            {{ $alertMessage }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form wire:submit="createMonthlyBillMerge" id="user-form">
            <div class="list-group px-1 w-100">
                @foreach ($items as $key => $item)
                <label for="contribution-{{ $key }}" class="list-group-item list-group-item-action my-1 rounded {{ in_array($item->id, (array) $dues_payment_ids) ? 'active' : '' }}" style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="w-50">
                            <div class="custom-control custom-checkbox d-none">
                                <input type="checkbox" wire:model="dues_payment_ids" class="custom-control-input bill-list" id="contribution-{{ $key }}" value="{{ $item->id }}" autocomplete="off">
                            </div>
                            <p class="fs-6 w-75 text-left text-truncate font-weight-bold my-0">
                                {{ format_month_year($item->duesMonth->month, $item->duesMonth->year) }}
                            </p>
                        </div>
                        <div class="d-flex flex-column text-right w-50">
                            <div class="label-currency fs-5 w-100 font-weight-bold {{ in_array($item->id, (array) $dues_payment_ids) ? 'text-white' : 'text-success' }}">
                                <span class="fs-7 opacity-7 font-weight-normal">Rp</span>
                                {{ number_format($item->final_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
        </form>
        @else
        <div class="d-flex justify-content-center align-items-center" style="height: 82vh;">
            <div class="text-center w-100">
                <p class="h6">{{ __('Data tagihan bulanan tidak ditemukan. silahkan coba lagi atau hubungi admin.') }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        <button wire:target="createMonthlyBillMerge" wire:loading.attr="disabled" type="button" class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0" data-dismiss="modal">
            {{ __('label.cancel') }}
        </button>
        
        @if ($items->count() > 0)
        <button wire:loading.remove type="submit" form="user-form" class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">
            {{ __('label.save') }}
        </button>
        <button wire:loading class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>
        @endif
    </div>
</div>

@script
<script>
    $(function () {
        generateScrollbar();
    });

    $(document).on("change", ".bill-list", function (e) {
        let t = $(e.currentTarget);
        let isChecked = t.prop("checked");
        let listGroup = t.closest(".list-group-item");
        if (isChecked) {
            listGroup.addClass("active");
            listGroup.find(".label-currency").removeClass("text-success").addClass("text-white");
        } else {
            listGroup.removeClass("active");
            listGroup.find(".label-currency").removeClass("text-white").addClass("text-success");
        }
    });
</script>
@endscript
