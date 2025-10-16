<?php

namespace App\Filament\Resources\AreaResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Area;

class ListAreasOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Data', Area::count())
                ->description('All registered areas in database')
                ->icon('heroicon-o-map')
                ->color('primary'),

            Card::make('Areas', Area::whereNull('parent_id')->count())
                ->description('Top-level areas')
                ->icon('heroicon-o-rectangle-stack')
                ->color('success'),

            Card::make('Sub Areas', Area::whereNotNull('parent_id')->count())
                ->description('Areas under another parent')
                ->icon('heroicon-o-building-office')
                ->color('warning'),

//            Card::make('Type Count', Area::select('type')->distinct()->count())
//                ->description('Unique area types')
//                ->icon('heroicon-o-tag')
//                ->color('info'),
        ];
    }
}
