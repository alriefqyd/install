<?php

namespace App\Filament\Resources\InstrumentIndexResource\Pages;

use App\Filament\Resources\InstrumentIndexResource;
use App\Models\InstrumentIndex;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

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

        if ($this->actionType === 'reject' && empty($this->rejectionReason)) {
            Notification::make()
                ->title('Rejection reason is required.')
                ->danger()
                ->send();
            return;
        }

        // Update DB status
        foreach ($this->records as $record) {
            $record->update([
                'status_updated' => $this->actionType === 'approve' ? 'Approved' : 'Rejected',
//                'rejection_reason' => $this->actionType === 'reject' ? $this->rejectionReason : null,
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
