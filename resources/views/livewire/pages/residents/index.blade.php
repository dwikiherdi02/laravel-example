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
    $path = '/residents';
    $menu = $service->getMenuBySlug($path);
    $this->title = __($menu->name_lang_key ?? '');
    $this->icon = $menu->icon ?? '';
    return $this->title;
});

?>

<div>
    <x-page-heading :title="$title" :icon="$icon">
        {{-- <div class="d-inline-block text-right">
            <button type="button" class="btn-shadow btn-icon btn btn-success">
                <i class="pe-7s-plus btn-icon-wrapper"></i>
                {{ __('label.add') }}
            </button>
        </div> --}}
    </x-page-heading>
    <div class="row">
        <div class="col-md-12">
            <livewire:pages.residents.table />
        </div>
    </div>

    <!-- Modal -->
    @push('modals')
        <livewire:pages.residents.modal />
    @endpush
</div>
