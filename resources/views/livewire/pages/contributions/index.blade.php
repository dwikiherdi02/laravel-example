<?php

use App\Services\ComponentService;

use function Livewire\Volt\{layout, state, title, action, on};

layout('layouts.app');

state([
    'title',
    'icon',
]);

title(function (ComponentService $service) {
    $path = '/contributions';
    $menu = $service->getMenuBySlug($path);
    $this->title = __($menu->name_lang_key ?? '');
    $this->icon = $menu->icon ?? '';
    return $this->title;
});

?>

<div>
    <x-page-heading :title="$title" :icon="$icon" />
    <div class="row">
        <div class="col-md-12">
            <livewire:pages.contributions.list />
        </div>
    </div>

    @push('modals')
        <div class="modal modal-fullscreen px-0 fade" id="modal-contribution" tabindex="-1" role="dialog"
            aria-labelledby="modal-resident-title" aria-hidden="true"
            data-animate-in="animate__animated animate__fadeInRight animate__faster"
            data-animate-out="animate__animated animate__fadeOutRight animate__faster">
            <div class="modal-dialog modal-dialog-centered shadow-none" role="document">
                <div class="modal-content overflow-hidden">
                    <livewire:pages.contributions.modal-content />
                </div>
            </div>
        </div>
    @endpush
</div>

@script
    <script>
        $(function () {
            $("#modal-contribution").on("hidden.bs.modal", function (e) {
                window.dispatchEvent(new CustomEvent('fetchModalContributionContentJs'));
            });
        });
    </script>
@endscript
