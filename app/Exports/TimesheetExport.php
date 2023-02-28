<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketHour;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimesheetExport implements FromCollection, WithHeadings
{
    protected array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function headings(): array
    {
        return [
            '#',
            'Project',
            'Ticket',
            'Details',
            'User',
            'Time',
            'Hours',
            'Activity',
            'Date',
        ];
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $collection = collect();

        $hours = TicketHour::where('user_id', auth()->user()->id)
            ->whereBetween('created_at', [$this->params['start_date'], $this->params['end_date']])
            ->get();

        foreach ($hours as $item) {
            $collection->push([
                '#' => $item->ticket->code,
                'project' => $item->ticket->project->name,
                'ticket' => $item->ticket->name,
                'details' => $item->comment,
                'user' => $item->user->name,
                'time' => $item->forHumans,
                'hours' => number_format($item->value, 2, ',', ' '),
                'activity' => $item->activity ? $item->activity->name : '-',
                'date' => $item->created_at->format(__('Y-m-d g:i A')),
            ]);
        }

        return $collection;
    }
}
