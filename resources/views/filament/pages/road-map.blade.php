<x-filament::page>

    <x-filament::card>

        <form wire:submit.prevent="filter" class="flex items-center gap-2 min-w-[16rem]">
            {{ $this->form }}
            <button type="submit"
                    class="px-3 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded">
                <x-heroicon-o-search class="w-6 h-6" />
            </button>
        </form>

        <div class="relative gantt" id="gantt-chart" wire:ignore></div>
    </x-filament::card>

</x-filament::page>

@push('scripts')
    <link rel="stylesheet" href="{{ asset('css/jsgantt.css') }}" />
    <script src="{{ asset('js/jsgantt.js') }}"></script>

    <script>
        const g = new JSGantt.GanttChart(document.getElementById('gantt-chart'), 'day');
        // Set settings
        g.setOptions({
            vCaptionType: 'Complete',
            vDayColWidth: 26,
            vWeekColWidth: 52,
            vMonthColWidth: 52,
            vDateTaskDisplayFormat: 'day dd month yyyy',
            vDayMajorDateDisplayFormat: 'mon yyyy - Week ww',
            vWeekMinorDateDisplayFormat: 'dd mon',
            vLang: '{{ config('app.locale') }}',
            vShowTaskInfoLink: 1,
            vShowEndWeekDate: 0,
            vUseSingleCell: 10000,
            vFormatArr: ['Day', 'Week', 'Month'],
        });
        // Parse json
        JSGantt.parseJSON('{{ asset('gantt.json') }}', g);
        // Customize gantt chart
        g.setShowDur(false); // Hide duration from columns
        g.setUseToolTip(false); // Remove tooltip on object hover
        g.setMinDate(new Date(2022, 0, 1)); // Set min date
        g.setMaxDate(new Date(2022, 11, 31)); // Set max date
        g.setScrollTo(new Date(2022, 2, 26)); // Scroll to first object
        // Draw gantt chart
        g.Draw();

        window.addEventListener('projectChanged', (e) => {
            g.ClearTasks();
            JSGantt.parseJSON('/gantt-' + e.detail.project + '.json', g);
            g.Draw();
        });
    </script>
@endpush
