<?php

namespace App\Http\Controllers;

use App\Filament\Resources\InstrumentIndexResource;
use App\Models\InstrumentIndex;
use Illuminate\Http\Request;

class InstrumentIndexController extends Controller
{
    public function viewInstrumentIndexByTicketNumber(Request $request)
    {
        $ticketNumber = $request->ticket_number;

        // Optional: check if exists
        if (!InstrumentIndex::where('ticket_number', $ticketNumber)->exists()) {
            abort(404, 'Ticket not found');
        }

        return redirect(InstrumentIndexResource::getUrl('ticket', [
            'ticket_number' => $ticketNumber,
        ]));
    }

    public function success()
    {
        return view('instrumentIndex.successFinalize');
    }
}
