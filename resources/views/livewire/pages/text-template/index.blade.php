<?php

use App\Services\ComponentService;

use function Livewire\Volt\{layout, state, title};

layout('layouts.app');

state([
    'title',
    'icon',
]);

title(function (ComponentService $service) {
    $path = '/text-template';
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
            <livewire:pages.text-template.list />
        </div>
    </div>

    @push('modals')
        <div class="modal modal-fullscreen px-0 fade" id="modal-text-template" tabindex="-1" role="dialog"
            aria-labelledby="modal-resident-title" aria-hidden="true"
            data-animate-in="animate__animated animate__fadeInRight animate__faster"
            data-animate-out="animate__animated animate__fadeOutRight animate__faster">
            <div class="modal-dialog modal-dialog-centered shadow-none" role="document">
                <div class="modal-content overflow-hidden">
                    <livewire:pages.text-template.modal-content />
                </div>
            </div>
        </div>
    @endpush
</div>

@script
    <script>
        $(function () {
            $("#modal-text-template").on("hidden.bs.modal", function (e) {
                window.dispatchEvent(new CustomEvent('fetchModalTextTemplateContentJs'));
            });
        });
    </script>
@endscript