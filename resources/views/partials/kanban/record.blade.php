<div class="kanban-record" data-id="{{ $record['id'] }}">
    <button type="button" class="handle">
        <x-heroicon-o-arrows-expand class="w-5 h-5" />
    </button>
    <div class="record-info">
        @if($this->isMultiProject())
            <span class="record-subtitle">
                {{ $record['project']->name }}
            </span>
        @endif
        <a href="{{ route('filament.resources.tickets.view', $record['id']) }}"
           target="_blank"
           class="record-title">
            {{ $record['title'] }}
        </a>
    </div>
    <div class="record-footer">
        <div class="record-type-code">
            <x-ticket-priority :priority="$record['priority']" />
            <x-ticket-type :type="$record['type']" />
            <span>{{ $record['code'] }}</span>
        </div>
        @if($record['responsible'])
            <x-user-avatar :user="$record['responsible']" />
        @endif
    </div>
</div>
