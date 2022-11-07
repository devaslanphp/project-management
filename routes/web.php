<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::get('/tickets/share/{ticket:code}', function (Ticket $ticket) {
    return redirect()->to(route('filament.resources.tickets.view', $ticket));
})->name('filament.resources.tickets.share');
