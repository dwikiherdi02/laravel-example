<?php

use App\Services\ComponentService;

use function Livewire\Volt\{layout, state, title, action, on};

layout('layouts.app');

state([
    'title',
    'icon',
]);

title(function (ComponentService $service) {
    // $path = '/' . app('request')->uri()->path();
    $path = '/users';
    $menu = $service->getMenuBySlug($path);
    $this->title = __($menu->name_lang_key ?? '');
    $this->icon = $menu->icon ?? '';
    return $this->title;
});

?>

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.min.css') }}"> --}}
@endpush

<div>
    <x-page-heading :title="$title" :icon="$icon" />
    <div class="row">
        <div class="col-md-12">
            <livewire:pages.users.list />
        </div>
    </div>

    @push('modals')
        <div class="modal modal-fullscreen px-0 fade" id="modal-user" tabindex="-1" role="dialog"
            aria-labelledby="modal-resident-title" aria-hidden="true"
            data-animate-in="animate__animated animate__fadeInRight animate__faster"
            data-animate-out="animate__animated animate__fadeOutRight animate__faster">
            <div class="modal-dialog modal-dialog-centered shadow-none" role="document">
                <div class="modal-content overflow-hidden">
                    <livewire:pages.users.modal-content />
                </div>
            </div>
        </div>
    @endpush
</div>

@script
    <script>
        $(function () {
            $("#modal-user").on("hidden.bs.modal", function (e) {
                /* window.dispatchEvent(new Event('reloadDataUserJs')); */
                window.dispatchEvent(new CustomEvent('fetchModalUserContentJs'));
            });
        });
    </script>
@endscript

{{-- @push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" defer></script>
@endpush --}}
