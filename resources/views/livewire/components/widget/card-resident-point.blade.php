<?php
use App\Enum\RoleEnum;
use App\Services\ResidentPointService;

use function Livewire\Volt\{state, mount};

state([
    'point' => 0,
]);

mount(function (ResidentPointService $service) {
    $this->point = 0;
    if (auth_role() == RoleEnum::Warga->value) {
        $auth = Auth::user();
        $residentId = $auth->resident->id;
        $point = $service->getPointByResidentId($residentId);
        if ($point) {
            $this->point = $point->total_point;
        } else {
            $this->point = 0;
        }
    }
});

?>
<div>
    <div class="card">
        <div class="card-body p-3">
            <div class="d-flex flex-column">
                <div class="fs-6 text-muted p-0 m-0">
                    {{ __('widget.point') }}
                </div>
                <div class="fs-5 text-primary p-0 m-0">
                    {{ number_format($point, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>
