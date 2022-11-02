
{{-- Injected variables $record, $styles --}}
<a
    id="{{ $record['id'] }}"
    @if($recordClickEnabled)
        target="_blank"
        href="{{ route('filament.resources.tickets.view', $record['id']) }}"
    @endif
    class="{{ $styles['record'] }}">

    @include($recordContentView, [
        'record' => $record,
        'styles' => $styles,
    ])

</a>
