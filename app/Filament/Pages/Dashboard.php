<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AreaResource\Widgets\ListAreasOverview;
use App\Filament\Resources\LoopNumberRequestResource\Widgets\ListLoopNumberRequestOverview;
use App\Filament\Widgets\QuickLinksDashboardWidget;
use App\Models\LoopNumberRequest;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            ListLoopNumberRequestOverview::class,
            ListAreasOverview::class,
            QuickLinksDashboardWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }
}
