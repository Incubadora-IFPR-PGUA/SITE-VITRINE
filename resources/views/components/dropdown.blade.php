@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$isRight = ($align === 'right');
$widthRem = ($width === '48') ? '12rem' : '12rem';
@endphp

<div class="dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition
            class="dropdown-panel dropdown-align-{{ $align }}"
            style="display: none; width: {{ $widthRem }}; margin-top: 0.5rem;"
            @click="open = false">
        <div class="dropdown-inner">
            {{ $content }}
        </div>
    </div>
</div>

<style>
.dropdown-wrap { position: relative; display: inline-block; }
.dropdown-panel {
    position: absolute;
    z-index: 9999;
    border-radius: 0.375rem;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    transform-origin: top right;
    top: 100%;
    right: 0;
}
.dropdown-panel.dropdown-align-left { left: 0; right: auto; transform-origin: top left; }
.dropdown-inner { border-radius: 0.375rem; }
</style>
