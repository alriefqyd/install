<?php

namespace App\Filament\Resources\InstrumentIndexResource\Pages;

use App\Filament\Resources\InstrumentIndexResource;
use App\Mail\SendApprovedInstrumentIndexMail;
use App\Mail\SendApprovedLoopNumberMail;
use App\Mail\SendRejectInstrumentIndexMail;
use App\Models\InstrumentIndex;
use App\Models\LoopNumberRequest;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class ViewInstrumentIndexByTicket extends Page
{
    protected static string $resource = InstrumentIndexResource::class;

    protected static string $view = 'filament.resources.instrument-index-resource.pages.view-instrument-index-by-ticket';

    public ?Collection $records = null;
    public ?string $ticket_number = null;

    // 👇 Add these Livewire properties
    public ?string $actionType = null;
    public ?string $rejectionReason = null;

    public function mount(string $ticket_number): void
    {
        $this->ticket_number = $ticket_number;
        $this->records = InstrumentIndex::with(['areas','services'])->where('ticket_number', $ticket_number)
            ->orderByDesc('created_at')
            ->get();
    }

    public function confirmAction(): void
    {
        if (! $this->actionType) {
            Notification::make()
                ->title('Please select Approve or Reject first.')
                ->danger()
                ->send();
            return;
        }

        $instruments = InstrumentIndex::with('loopNumberRequest.engineers')->where('ticket_number', $this->ticket_number)->get();
        if ($this->actionType === 'reject'){
            if (empty($this->rejectionReason)) {
                Notification::make()
                    ->title('Rejection reason is required.')
                    ->danger()
                    ->send();
                return;
            }
            foreach ($instruments as $instrument) {
                $instrument->update([
                    'is_finalize' => false
                ]);
            }
            Mail::to($instruments[0]->loopNumberRequest?->engineers?->email)->send(new SendRejectInstrumentIndexMail($instruments[0], $this->rejectionReason));
        }

        if($this->actionType === 'approve'){
            LoopNumberRequest::where('ticket_number', $this->ticket_number)->update([
                'is_completed' => true
            ]);

            Mail::to($instruments[0]->loopNumberRequest?->engineers?->email)->send(new SendApprovedInstrumentIndexMail($instruments[0] ,$this->ticket_number));
        }

        // Update DB status
        foreach ($this->records as $record) {
            $record->update([
                'status_updated' => $this->actionType === 'approve' ? 'Approved' : 'Rejected',
                //'rejection_reason' => $this->actionType === 'reject' ? $this->rejectionReason : null,
                'is_finalize' => $this->actionType === 'approve' ? 1 : 0,
            ]);
        }

        Notification::make()
            ->title("Ticket has been " . ucfirst($this->actionType) . " successfully.")
            ->success()
            ->send();

        // Reset modal inputs
        $this->actionType = null;
        $this->rejectionReason = null;

        // Refresh the list
        $this->records = InstrumentIndex::where('ticket_number', $this->ticket_number)->get();
    }
}
