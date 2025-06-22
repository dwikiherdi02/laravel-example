<?php
use App\Services\SystemBalanceService;

use function Livewire\Volt\{state, mount};

state([
    'balance' => 0,
]);

mount(function (SystemBalanceService $service) {
    $balance = $service->getBalance();
    if ($balance) {
        $this->balance = $balance->final_balance;
    } else {
        $this->balance = 0;
    }
});

?>
<div>
    <div class="card">
        <div class="card-body p-3">
            <div class="d-flex flex-column">
                <div class="fs-6 text-muted p-0 m-0">
                    {{ __('Saldo') }}
                </div>
                <div class="fs-5 text-success p-0 m-0">
                    <span class="fs-6 opacity-5">Rp</span>
                    {{ number_format($balance, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>
