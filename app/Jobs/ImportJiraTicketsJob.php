<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\ProjectUser;
use App\Models\Ticket;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\TicketType;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportJiraTicketsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tickets;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tickets, $user)
    {
        $this->tickets = $tickets;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->tickets && sizeof($this->tickets)) {
            foreach ($this->tickets as $ticket) {
                $projectDetails = $ticket->fields->project;
                $ticketData = $ticket->fields;

                $project = Project::where('name', $projectDetails->name)->first();
                if (!$project) {
                    $project = Project::create([
                        'name' => $projectDetails->name,
                        'description' => __('Project imported from Jira, project key:') . $projectDetails->key,
                        'status_id' => ProjectStatus::where('is_default', true)->first()->id,
                        'owner_id' => $this->user->id,
                        'ticket_prefix' => $projectDetails->key
                    ]);

                    ProjectUser::create([
                        'project_id' => $project->id,
                        'user_id' => $this->user->id,
                        'role' => config('system.projects.affectations.roles.can_manage')
                    ]);
                }

                Ticket::create([
                    'name' => $ticketData->summary,
                    'content' => $ticketData->description ?? __('No content found in jira ticket'),
                    'owner_id' => $this->user->id,
                    'status_id' => TicketStatus::where('is_default', true)->first()->id,
                    'project_id' => $project->id,
                    'type_id' => TicketType::where('is_default', true)->first()->id,
                    'priority_id' => TicketPriority::where('is_default', true)->first()->id,
                ]);
            }
            FilamentNotification::make()
                ->title(__('Jira importation'))
                ->icon('heroicon-o-cloud-download')
                ->body(__('Jira tickets successfully imported'))
                ->sendToDatabase($this->user);
        }
    }
}
