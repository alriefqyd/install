<?php

namespace App\Filament\Resources\InstrumentIndexResource\Pages;

use App\Filament\Resources\InstrumentIndexResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstrumentIndex extends EditRecord
{
    protected static string $resource = InstrumentIndexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
