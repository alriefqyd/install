<?php

namespace App\Filament\Resources\LoopNumberRequestResource\Widgets;

use App\Models\LoopNumberRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ListLoopNumberRequestOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return true;
    }

    protected function getCards(): array
    {
        return [
            Card::make('Total Data', LoopNumberRequest::count())
                ->description('All loop number request in database')
                ->icon('heroicon-o-map')
                ->color('primary'),
            Card::make('Total Data', LoopNumberRequest::where('status','approve')->count())
                ->description('All loop number approved in database')
                ->icon('heroicon-o-map')
                ->color('primary'),
            Card::make('Total Data', LoopNumberRequest::where('status','pending')->count())
                ->description('All loop number waiting for approval')
                ->icon('heroicon-o-map')
                ->color('primary'),
        ];
    }
}
