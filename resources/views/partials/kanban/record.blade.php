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
            <span class="code">{{ $record['code'] }}</span>
            <span class="title">{{ $record['title'] }}</span>
        </a>
    </div>
    <div class="record-footer">
        <div class="record-type-code">
            @php($epic = $record['epic'])
            @if($epic && $epic != "")
                <div class="px-2 py-1 rounded flex items-center justify-center text-center text-xs text-white
                            bg-purple-500" title="{{ __('Epic') }}">
                    {{ $epic->name }}
                </div>
            @endif
            <x-ticket-priority :priority="$record['priority']" />
            <x-ticket-type :type="$record['type']" />
        </div>
        @if($record['responsible'])
            <x-user-avatar :user="$record['responsible']" />
        @endif
    </div>
    @if($record['relations']?->count())
        <div class="record-relations">
            @foreach($record['relations'] as $relation)
                <div>
                    <span class="type text-{{ config('system.tickets.relations.colors.' . $relation->type) }}-600">
                        {{ __(config('system.tickets.relations.list.' . $relation->type)) }}
                    </span>
                    <a target="_blank" class="relation"
                        href="{{ route('filament.resources.tickets.share', $relation->relation->code) }}">
                        {{ $relation->relation->code }}
                    </a>
                </div>
            @endforeach
        </div>
    @endif
    @if($record['totalLoggedHours'])
        <div class="record-logged-hours">
            <x-heroicon-o-clock class="w-4 h-4" /> {{ $record['totalLoggedHours'] }}
        </div>
    @endif
</div>
