<?php

namespace App\Filament\Resources\AreaResource\Pages;

use App\Filament\Resources\AreaResource;
use App\Models\Area;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ListAreas extends ListRecords
{
    protected static string $resource = AreaResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
//            AreaResource\Widgets\ListAreasOverview::class, // Register custom widget here
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            // 🔹 Filter by Type (dropdown)
            SelectFilter::make('type')
                ->label('Area Type')
                ->options(
                    Area::query()
                        ->whereNotNull('type')
                        ->distinct()
                        ->pluck('type', 'type')
                        ->toArray()
                ),

            // 🔹 Filter by Parent Area
            SelectFilter::make('parent_id')
                ->label('Parent Area')
                ->options(
                    Area::whereNull('parent_id')
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->placeholder('All'),

            // 🔹 Filter for Only Top-level Areas
            SelectFilter::make('is_parent')
                ->label('Show Only Parent Areas')
                ->trueLabel('Parent only')
                ->falseLabel('Sub-areas only')
                ->queries(
                    true: fn ($query) => $query->whereNull('parent_id'),
                    false: fn ($query) => $query->whereNotNull('parent_id'),
                ),
        ];
    }
}
