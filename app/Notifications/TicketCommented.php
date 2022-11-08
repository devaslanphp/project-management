<?php

namespace App\Notifications;

use App\Models\TicketComment;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCommented extends Notification implements ShouldQueue
{
    use Queueable;

    private TicketComment $ticketComment;

    /**
     * Create a new notification instance.
     *
     * @param TicketComment $ticket
     * @return void
     */
    public function __construct(TicketComment $ticketComment)
    {
        $this->ticketComment = $ticketComment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line(
                __(
                    'A new comment has been added to the ticket :ticket by :name.',
                    [
                        'ticket' => $this->ticketComment->ticket->name,
                        'name' => $this->ticketComment->user->name
                    ]
                )
            )
            ->line(__('See more details of this ticket by clicking on the button below:'))
            ->action(
                __('View details'),
                route('filament.resources.tickets.share', $this->ticketComment->ticket->code)
            );
    }

    public function toDatabase(User $notifiable): array
    {
        return FilamentNotification::make()
            ->title(
                __(
                    'Ticket :ticket commented',
                    [
                        'ticket' => $this->ticketComment->ticket->name
                    ]
                )
            )
            ->icon('heroicon-o-ticket')
            ->body(fn() => __('by :name', ['name' => $this->ticketComment->user->name]))
            ->actions([
                Action::make('view')
                    ->link()
                    ->icon('heroicon-s-eye')
                    ->url(fn() => route('filament.resources.tickets.share', $this->ticketComment->ticket->code)),
            ])
            ->getDatabaseMessage();
    }
}
