<?php

namespace App\Filament\Resources\InstrumentIndexResource\Pages;

use App\Filament\Resources\InstrumentIndexResource;
use App\Mail\sendRejectInstrumentIndexMail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditInstrumentIndex extends EditRecord
{
    protected static string $resource = InstrumentIndexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
//        $record = $this->record;
//        try {
//            $requestor = 'Admin';
//            $requestor = Engineers::where('id', $record->engineers_id)->first();
//            if($record->status_updated == 'reject'){
//                Mail::to('al@vale.com')->send(new sendRejectInstrumentIndexMail($record));
//            }
//
//        } catch (\Exception $exception){
//            echo $exception->getMessage();
//        }

    }
}
