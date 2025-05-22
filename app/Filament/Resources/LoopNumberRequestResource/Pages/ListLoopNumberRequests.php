<?php

namespace App\Filament\Resources\LoopNumberRequestResource\Pages;

use App\Filament\Resources\LoopNumberRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoopNumberRequests extends ListRecords
{
    protected static string $resource = LoopNumberRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
