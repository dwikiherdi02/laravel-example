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

<div>
    <x-page-heading :title="$title" :icon="$icon" />
    <div class="row">
        <div class="col-md-12">
            <livewire:pages.users.list />
        </div>
    </div>
</div>
