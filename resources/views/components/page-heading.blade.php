@props([
    'title' => '',
    'description' => '',
    'icon' => '',
])

<div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="{{ $icon }} icon-gradient bg-happy-itmeo"></i>
                </div>
                <div>
                    {{ $title }}
                    @if (!empty($description))
                        <div class="page-title-subheading">{{ $description }} </div>
                    @endif
                </div>
            </div>
            <div class="page-title-actions">
                {{ $slot ?? '' }}
            </div>
        </div>
    </div>