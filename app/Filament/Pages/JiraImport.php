<?php

namespace App\Filament\Pages;
use BackedEnum;

use App\Helpers\JiraHelper;
use App\Jobs\ImportJiraTicketsJob;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use App\Settings\JiraSettings;

class JiraImport extends Page implements HasForms
{
    use InteractsWithForms, JiraHelper;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cloud-arrow-down';

    protected string $view = 'filament.pages.jira-import';

    protected static ?string $slug = 'jira-import';

    protected static ?int $navigationSort = 2;

    protected $listeners = [
        'updateJiraProjects',
        'updateJiraTickets'
    ];

    public $host;
    public $username;
    public $token;
    private $loadingProjects;
    private $projects;
    public $selected_projects;
    private $loadingTickets;
    private $tickets;
    public $selected_tickets;
    public $data = [];
    public $ticketsDataApi;
    public $epicKeys = [];
    public $taskKeys = [];

    public function mount(): void
    {
        $settings = app(JiraSettings::class);
        $this->form->fill([
            'host' => $settings->host,
            'username' => $settings->username,
            'token' => $settings->token,
        ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('Import from Jira');
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('Use this section to login into your jira account and import tickets to this application');
    }

    public static function getNavigationLabel(): string
    {
        return __('Jira import');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public function getFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    Wizard::make([
                        Wizard\Step::make(__('Jira login'))
                            ->schema([
                                Placeholder::make('info')
                                    ->extraAttributes([
                                        'class' => 'bg-primary-500 rounded-lg border border-primary-600 text-white font-medium text-sm py-3 px-4'
                                    ])
                                    ->hiddenLabel()
                                    ->content(__('Your Jira credentials are used to communicate with the Jira REST API. They will be saved (with the API token encrypted) so you do not need to re-enter them each time.')),

                                Grid::make()
                                    ->schema([
                                        TextInput::make('host')
                                            ->label(__('Host'))
                                            ->helperText(__('The url used to access your jira account'))
                                            ->required(),

                                        TextInput::make('username')
                                            ->label(__('Username'))
                                            ->helperText(__('Your jira account username'))
                                            ->required(),

                                        TextInput::make('token')
                                            ->label(__('API Token'))
                                            ->helperText(__('Your jira account API Token'))
                                            ->password()
                                            ->required(),
                                    ]),
                            ])
                            ->afterValidation(function () {
                                $settings = app(JiraSettings::class);
                                $settings->host = $this->host;
                                $settings->username = $this->username;
                                $settings->token = $this->token;
                                $settings->save();

                                $this->loadingProjects = true;
                                $this->dispatch('updateJiraProjects');
                            }),

                        Wizard\Step::make(__('Jira projects'))
                            ->schema([
                                Placeholder::make('hint')
                                    ->extraAttributes([
                                        'class' => 'bg-primary-500 rounded-lg border border-primary-600 text-white font-medium text-sm py-3 px-4'
                                    ])
                                    ->hiddenLabel()
                                    ->visible(fn() => !$this->loadingProjects && $this->projects)
                                    ->content(__('Choose your jira projects to import')),

                                Placeholder::make('loading')
                                    ->extraAttributes([
                                        'class' => 'bg-warning-500 rounded-lg border border-warning-600 text-white font-medium text-sm py-3 px-4'
                                    ])
                                    ->hiddenLabel()
                                    ->visible(fn() => $this->loadingProjects)
                                    ->content(__('Loading projects, please wait...')),

                                Placeholder::make('info')
                                    ->extraAttributes([
                                        'class' => 'bg-danger-500 rounded-lg border border-danger-600 text-white font-medium text-sm py-3 px-4'
                                    ])
                                    ->hiddenLabel()
                                    ->visible(fn() => !$this->loadingProjects && !$this->projects)
                                    ->content(__('Your jira credentials are incorrect, please go to previous step and re-enter your jira credentials')),

                                CheckboxList::make('selected_projects')
                                    ->label(__('Jira projects'))
                                    ->required()
                                    ->visible(fn() => $this->projects)
                                    ->options(function () {
                                        $list = [];
                                        if ($this->projects) {
                                            foreach ($this->projects as $project) {
                                                $list[$project->key] = new HtmlString(
                                                    "<div class='w-full flex flex-col gap-1'>"
                                                    . "<div class='w-full flex items-center gap-1'>"
                                                    . "<img src='" . $project->avatarUrls->{'16x16'} . "' class='rounded-full w-8 h-8 shadow' />"
                                                    . "<span class='font-medium text-gray-700 text-base'>" . $project->name . "</span>"
                                                    . "<div class='text-gray-700 text-xs font-light'><span class='font-medium uppercase'>/</span> " . $project->key . "</div>"
                                                    . "</div>"
                                                    . "</div>"
                                                );
                                            }
                                        }
                                        return $list;
                                    }),

                            ])
                            ->afterValidation(function () {
                                $this->loadingTickets = true;
                                $this->dispatch('updateJiraTickets');
                            }),

                        Wizard\Step::make(__('Jira tickets'))
                            ->schema(function () {
                                $fields = [];

                                $fields[] = Placeholder::make('hint')
                                    ->extraAttributes([
                                        'class' => 'bg-primary-500 rounded-lg border border-primary-600 text-white font-medium text-sm py-3 px-4'
                                    ])
                                    ->hiddenLabel()
                                    ->visible(fn() => !$this->loadingTickets && $this->tickets)
                                    ->content(__('Choose your jira projects to import'));

                                $fields[] = Placeholder::make('loading')
                                    ->extraAttributes([
                                        'class' => 'bg-warning-500 rounded-lg border border-warning-600 text-white font-medium text-sm py-3 px-4'
                                    ])
                                    ->hiddenLabel()
                                    ->visible(fn() => $this->loadingTickets)
                                    ->content(__('Loading tickets, please wait...'));

                                if (!$this->loadingTickets && $this->tickets) {
                                    $fields[] = Placeholder::make('selection_buttons')
                                        ->hiddenLabel()
                                        ->content(new HtmlString(
                                            "<div class='flex items-center gap-2'>"
                                            . "<button type='button' wire:click='selectAllEpics' class='px-3 py-1.5 rounded-md text-xs font-bold bg-purple-600 text-white shadow-sm hover:bg-purple-700'>" . __('Select All Epics') . "</button>"
                                            . "<button type='button' wire:click='selectAllTasks' class='px-3 py-1.5 rounded-md text-xs font-bold bg-primary-600 text-white shadow-sm hover:bg-primary-700'>" . __('Select All Tasks') . "</button>"
                                            . "<button type='button' wire:click='deselectAll' class='px-3 py-1.5 rounded-md text-xs font-bold bg-gray-400 text-white shadow-sm hover:bg-gray-500'>" . __('Deselect All') . "</button>"
                                            . "</div>"
                                        ));
                                }

                                if (!$this->loadingTickets) {
                                    if ($this->tickets) {
                                        foreach ($this->tickets as $projectKey => $ticket) {
                                            if ($ticket['total'] > 0) {
                                                $fields[] = Placeholder::make('tickets_' . Str::slug($projectKey))
                                                    ->label(__('Tickets for the project:') . ' ' . $projectKey)
                                                    ->extraAttributes([
                                                        'style' => 'margin-bottom: -15px;'
                                                    ])
                                                    ->content('');

                                                foreach ($ticket['issues'] as $issue) {
                                                    $fields[] = Checkbox::make('data.' . Str::slug($projectKey) . '_' . Str::slug($issue['code']))
                                                        ->label(function () use ($issue) {
                                                            $epicBadge = !empty($issue['isEpic'])
                                                                ? "<span class='inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-600 text-white shadow-sm'>EPIC</span> "
                                                                : '';
                                                            return new HtmlString(
                                                                "<div class='w-full flex flex-col gap-1'>"
                                                                . "<div class='w-full flex items-center gap-1'>"
                                                                . $epicBadge
                                                                . "<div class='text-gray-700 text-xs font-light'><span class='font-medium uppercase'>" . $issue['code'] . "</span> " . $issue['name'] . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                            );
                                                        });
                                                }
                                            } else {
                                                $fields[] = Placeholder::make('no_tickets_' . Str::slug($projectKey))
                                                    ->label(__('Tickets for the project:') . ' ' . $projectKey)
                                                    ->content(__('No tickets found!'));
                                            }
                                        }
                                    } else {
                                        $fields[] = Placeholder::make('info')
                                            ->extraAttributes([
                                                'class' => 'bg-warning-500 rounded-lg border border-warning-600 text-white font-medium text-sm py-3 px-4'
                                            ])
                                            ->hiddenLabel()
                                            ->visible(fn() => !$this->projects)
                                            ->content(__('No tickets found!'));
                                    }
                                }
                                return $fields;
                            }),
                    ])
                        ->submitAction(new HtmlString("<button type='submit' class='px-3 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded'>" . __('Import') . "</button>")),
                ]),
        ];
    }

    public function import(): void
    {
        if ($this->data && sizeof($this->data)) {
            $tickets = [];
            foreach (array_keys($this->data) as $item) {
                $url = $this->ticketsDataApi[$item] ?? null;
                if ($url) {
                    $ticket = $this->getJiraTicketDetails($this->host, $this->username, $this->token, $url);
                    if ($ticket) {
                        $tickets[] = $ticket;
                    }
                }
            }
            dispatch(new ImportJiraTicketsJob($tickets, auth()->user()));
            Notification::make()
                ->success()
                ->title(__('The importation job is started, when finished you will be notified'))
                ->send();
            $this->redirect(route('filament.admin.pages.jira-import'));
        } else {
            Notification::make()
                ->warning()
                ->title(__('Please choose at least a jira ticket to import'))
                ->send();
        }
    }

    public function updateJiraProjects(): void
    {
        $client = $this->connectToJira($this->host, $this->username, $this->token);
        $this->projects = $this->getJiraProjects($client);
        $this->loadingProjects = false;
    }

    public function updateJiraTickets(): void
    {
        $this->ticketsDataApi = [];
        $this->epicKeys = [];
        $this->taskKeys = [];
        $client = $this->connectToJira($this->host, $this->username, $this->token);
        $this->tickets = $this->getJiraTicketsByProject($client, $this->selected_projects);
        if ($this->tickets) {
            foreach ($this->tickets as $projectKey => $ticket) {
                foreach ($ticket['issues'] as $issue) {
                    $key = Str::slug($projectKey) . '_' . Str::slug($issue['code']);
                    $this->ticketsDataApi[$key] = $issue['data']->self;
                    if (!empty($issue['isEpic'])) {
                        $this->epicKeys[] = $key;
                    } else {
                        $this->taskKeys[] = $key;
                    }
                }
            }
        }
        $this->loadingTickets = false;
    }

    public function selectAllEpics(): void
    {
        foreach ($this->epicKeys as $key) {
            $this->data[$key] = true;
        }
    }

    public function selectAllTasks(): void
    {
        foreach ($this->taskKeys as $key) {
            $this->data[$key] = true;
        }
    }

    public function deselectAll(): void
    {
        $this->data = [];
    }
}
