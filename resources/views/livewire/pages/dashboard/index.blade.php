<?php
use App\Services\ComponentService;

use function Livewire\Volt\{layout, state, title};


layout('layouts.app');

state([
    'title',
    'icon',
]);

title(function (ComponentService $service) {
    // $path = '/' . app('request')->uri()->path();
    $path = '/dashboard';
    $menu = $service->getMenuBySlug($path);
    $this->title = __($menu->name_lang_key);
    $this->icon = $menu->icon;
    return $this->title;
});


?>

<div>
    <x-page-heading :title="$title" :icon="$icon"></x-page-heading>

    <div class="row">
        <div class="col-md-6 col-lg-8 mb-3">
            <livewire:components.widget.card-welcome />
        </div>
        <div class="col-md-6 col-lg-4 mb-3">
            <livewire:components.widget.card-balance />
        </div>
    </div>
</div>
