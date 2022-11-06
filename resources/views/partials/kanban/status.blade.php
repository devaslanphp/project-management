<div class="kanban-statuses">
    <div class="status-header"
         style="border-color: {{ $status['color'] }}66;">
        <span>{{ $status['title'] }}</span>
        @if($status['size'])
            {{ $status['size'] }} {{ __($status['size'] > 1 ? 'tickets' : 'ticket') }}
        @endif
    </div>
    <div class="status-container"
         data-status="{{ $status['id'] }}"
         id="status-records-{{ $status['id'] }}">
        @foreach($this->getRecords()->where('status', $status['id']) as $record)
            @include('partials.kanban.record')
        @endforeach

        @if($status['add_ticket'])
            <a class="create-record"
               href="{{ route('filament.resources.tickets.create') }}"
               target="_blank">
                <x-heroicon-o-plus class="w-4 h-4" /> {{ __('Create ticket') }}
            </a>
        @endif
    </div>
</div>
