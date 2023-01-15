<x-filament::page>

    <x-filament::card>

        <div class="w-full lg:flex md:hidden sm:hidden hidden flex-col gap-5">
            <div class="w-full flex justify-between items-center">
                <form wire:submit.prevent="filter" class="flex items-center gap-2 min-w-[16rem]">
                    {{ $this->form }}
                    <button type="submit"
                            class="px-3 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded">
                        <x-heroicon-o-search class="w-6 h-6" wire:loading.remove />
                        <div wire:loading.flex>
                            <div class="lds-dual-ring w-4 h-4"></div>
                        </div>
                    </button>
                </form>
                <div class="flex items-center gap-2">
                    @if(auth()->user()->can('Create ticket'))
                        <button wire:click="createEpic" wire:loading.attr="disabled"
                                class="flex items-center gap-2 bg-primary-500 hover:bg-primary-600 px-3 py-1
                                text-white rounded">
                            <x-heroicon-o-plus class="w-4 h-4" /> {{ __('Epic') }}
                        </button>
                        <button wire:click="createTicket" wire:loading.attr="disabled"
                                class="flex items-center gap-2 bg-success-500 hover:bg-success-600 px-3 py-1
                                text-white rounded">
                            <x-heroicon-o-plus class="w-4 h-4" /> {{ __('Ticket') }}
                        </button>
                    @endif
                </div>
            </div>

            <div wire:init="filter" class="w-full relative gantt" id="gantt-chart" wire:ignore></div>
        </div>

        <div class="w-full 2xl:hidden xl:hidden lg:hidden md:flex sm:flex flex flex-col gap-2 text-center
                    items-center justify-center text-gray-500 font-medium">
            <x-heroicon-o-emoji-sad class="w-10 h-10" />
            <span>{{ __('Road Map chart is only available on large screen') }}</span>
        </div>
    </x-filament::card>

    @if($epic)
        <!-- Epic modal -->
        <div class="dialog-container">
            <div class="dialog dialog-lg">
                <div class="dialog-header">
                    {{ __($epic && $epic->id ? 'Update Epic' : 'Create Epic') }}
                </div>
                <div class="dialog-content">
                    @livewire('road-map.epic-form', ['epic' => $epic])
                </div>
            </div>
        </div>
    @endif

    @if($ticket)
        <!-- Epic modal -->
        <div class="dialog-container">
            <div class="dialog dialog-xl">
                <div class="dialog-header">
                    {{ __('Create ticket') }}
                </div>
                <div class="dialog-content">
                    @livewire('road-map.issue-form', ['project' => $project])
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <link rel="stylesheet" href="{{ asset('css/jsgantt.css') }}" />
        <script src="{{ asset('js/jsgantt.js') }}"></script>

        <script>
            const g = new JSGantt.GanttChart(document.getElementById('gantt-chart'), 'week');
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
                vEvents: {
                    taskname: (task) => {
                        const data = task.getAllData();
                        const meta = data.pDataObject.meta;
                        if (meta.epic) {
                            Livewire.emit('updateEpic', meta.id);
                        } else {
                            window.open('/tickets/share/' + meta.slug, '_blank');
                        }
                    }
                }
            });
            // Customize gantt chart
            g.setShowDur(false); // Hide duration from columns
            g.setUseToolTip(false); // Remove tooltip on object hover
            // Draw gantt chart
            g.Draw();

            window.addEventListener('projectChanged', (e) => {
                g.ClearTasks();
                JSGantt.parseJSON(e.detail.url, g);
                const minDate = e.detail.start_date.split('-');
                const maxDate = e.detail.end_date.split('-');
                const scrollToDate = e.detail.scroll_to.split('-');
                g.setMinDate(new Date(minDate[0], (+minDate[1]) - 1, minDate['2']));
                g.setMaxDate(new Date(maxDate[0], (+maxDate[1]) - 1, maxDate['2']));
                g.setScrollTo(new Date(scrollToDate[0], (+scrollToDate[1]) - 1, scrollToDate['2']));
                g.Draw();
            });
        </script>
    @endpush

</x-filament::page>
