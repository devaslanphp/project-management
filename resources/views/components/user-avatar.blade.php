@if($user)
    <div>
        @php($uniqid = uniqid())
        <img src="{{ $user->avatar_url }}"
             alt="{{ $user->name }}"
             data-popover-target="popover-user-{{ $user->id }}-{{ $uniqid }}"
             class="w-6 h-6 rounded-full bg-gray-200 bg-cover bg-center"/>

        <div data-popover id="popover-user-{{ $user->id }}-{{ $uniqid }}" role="tooltip"
             class="inline-block absolute invisible z-10 w-64 text-sm font-light text-gray-500
                                        bg-white rounded-lg border border-gray-200 shadow-sm opacity-0
                                        transition-opacity duration-300 dark:text-gray-400 dark:bg-gray-800
                                        dark:border-gray-600">
            <div class="p-3">
                <div class="flex justify-between items-center mb-2">
                    <img class="w-10 h-10 rounded-full"
                         src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                </div>
                <p class="text-base font-semibold leading-none text-gray-900 dark:text-white">
                    <a>{{ $user->name }}</a>
                </p>
                <p class="mb-3 text-sm font-normal">
                    <a href="mailto:{{ $user->email }}"
                       class="hover:underline">
                        {{ $user->email }}
                    </a>
                </p>
                <p class="mb-4 text-sm font-light">
                    {{ __('Member since') }}
                    <a class="text-blue-600 dark:text-blue-500">
                        {{ $user->created_at->format('Y-m-d') }}
                    </a>
                </p>
                <ul class="flex text-sm font-light">
                    <li class="mr-2">
                        <div>
                        <span class="font-semibold text-gray-900 dark:text-white">
                            {{ collect(($user->ticketsOwned ?? collect())
                                    ->merge(($user->ticketsResponsible ?? collect())))->unique('id')->count() }}
                        </span>
                            <span>{{ __('Tickets') }}</span>
                        </div>
                    </li>
                    <li>
                        <div>
                        <span class="font-semibold text-gray-900 dark:text-white">
                            {{ collect(($user->projectsOwning ?? collect())
                                ->merge(($user->projectsAffected ?? collect())))->unique('id')->count() }}
                        </span>
                            <span>{{ __('Projects') }}</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div data-popper-arrow></div>
        </div>
    </div>
@endif
