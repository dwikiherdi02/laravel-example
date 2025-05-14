<?php

use App\Services\ComponentService;

use function Livewire\Volt\{layout, state, title, action, on};

layout('layouts.app');

state([
    'title',
    'icon',
    'isAddPage' => false,
]);

title(function (ComponentService $service) {
    // $path = '/' . app('request')->uri()->path();
    $path = '/residents';
    $menu = $service->getMenuBySlug($path);
    $this->title = __($menu->name_lang_key ?? '');
    $this->icon = $menu->icon ?? '';
    return $this->title;
});

on(['onChangePage']);

$onChangePage = action(function (bool $status = false) {
    $this->isAddPage = $status;
});

?>

<div>
    <x-page-heading :title="$title" :icon="$icon">
        @if(!$isAddPage)
            <div class="d-inline-block text-right">
                <button wire:click="$dispatch('onChangePage', { status: true })" type="button" class="btn-shadow btn-icon btn btn-success">
                    <i class="pe-7s-plus btn-icon-wrapper"></i>
                    {{ __('label.add') }}
                </button>
            </div>
        @endif
    </x-page-heading>
    <div class="row">
        <div wire:loading class="col-md-12">
            Loading...
        </div>
        <div wire:loading.remove class="col-md-12">
            @if(!$isAddPage)
            <livewire:pages.residents.table />
            @else
            <livewire:pages.residents.add />
            @endif
        </div>
    </div>
</div>
