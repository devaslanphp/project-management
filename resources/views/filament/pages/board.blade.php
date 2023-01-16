<x-filament::page>

    <div class="w-full flex flex-col gap-10 justify-center items-center">
        <form wire:submit.prevent="search" class="lg:w-[50%] w-full">
            {{ $this->form }}
        </form>

        <div class="w-full flex justify-center items-center text-center">
            <img src="{{ asset('img/scrum-kanban.png') }}"
                 class="lg:w-[50%] w-full rounded-2xl shadow border border-gray-300"
                 alt="Scrum board VS Kanban board"/>
        </div>
    </div>

</x-filament::page>
