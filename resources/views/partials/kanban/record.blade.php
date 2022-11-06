<div class="kanban-record" data-id="{{ $record['id'] }}">
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
            <x-ticket-type :type="$record['type']" />
            <span>{{ $record['code'] }}</span>
        </div>
        @if($record['responsible'])
            <img src="{{ $record['responsible']->avatar_url }}"
                 alt="{{ $record['responsible']->name }}"
                 class="avatar"
                 title="{{ $record['responsible']->name }}" />
        @endif
    </div>
</div>
