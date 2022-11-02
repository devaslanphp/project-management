{{-- Injected variables $status, $styles --}}
<div class="{{ $styles['kanbanHeader'] }}"
     style="background-color: {{ $status['bg-color'] }}; border-color: {{ $status['border-color'] }};">
    {{ $status['title'] }}
</div>
