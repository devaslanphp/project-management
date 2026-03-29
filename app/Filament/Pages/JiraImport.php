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

    public function mount(): void
    {
        $this->form->fill();
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
                                    ->content(__('Important: Your jira credentials are only used to communicate with jira REST API, and will not be stored in this application')),

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
                                $this->loadingProjects = true;
                                $this->emit('updateJiraProjects');
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
                                $this->emit('updateJiraTickets');
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
                                                            return new HtmlString(
                                                                "<div class='w-full flex flex-col gap-1'>"
                                                                . "<div class='w-full flex items-center gap-1'>"
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
                $url = $this->ticketsDataApi[$item];
                $tickets[] = $this->getJiraTicketDetails($this->host, $this->username, $this->token, $url);
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
        $client = $this->connectToJira($this->host, $this->username, $this->token);
        $this->tickets = $this->getJiraTicketsByProject($client, $this->selected_projects);
        foreach ($this->tickets as $projectKey => $ticket) {
            foreach ($ticket['issues'] as $issue) {
                $this->ticketsDataApi[Str::slug($projectKey) . '_' . Str::slug($issue['code'])] = $issue['data']->self;
            }
        }
        $this->loadingTickets = false;
    }
}
