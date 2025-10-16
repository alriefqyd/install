<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaResource\Pages;
use App\Filament\Resources\AreaResource\RelationManagers;
use App\Models\Area;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('code'),
                Select::make('type')->options([
                    'AREA' => 'AREA',
                    'SUB_AREA' => 'SUB AREA',
                ])->reactive(),
                Select::make('parent_id')->options(function (){
                    return Area::where('type','AREA')->pluck('name','id');
                })->searchable()
                    ->visible(fn ($get) => $get('type') === 'SUB_AREA'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Code')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge() // 💡 make it look like a colored tag
                    ->color(fn (string $state): string => match ($state) {
                        'AREA' => 'success',
                        'ZONE' => 'info',
                        'SECTION' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('parent.name')
                    ->label('Parent Area')
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Area Type')
                    ->options(
                        \App\Models\Area::query()
                            ->whereNotNull('type')
                            ->distinct()
                            ->pluck('type', 'type')
                            ->toArray()
                    )
                    ->indicator('Type'),

                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Parent Area')
                    ->options(
                        \App\Models\Area::whereNull('parent_id')->pluck('name', 'id')->toArray()
                    )
                    ->indicator('Parent'),

                Tables\Filters\TernaryFilter::make('is_parent')
                    ->label('Parent Level')
                    ->trueLabel('Parent Only')
                    ->falseLabel('Sub-area Only')
                    ->placeholder('All')
                    ->queries(
                        true: fn ($query) => $query->whereNull('parent_id'),
                        false: fn ($query) => $query->whereNotNull('parent_id'),
                    ),
//                Tables\Filters\TrashedFilter::make(),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::Dropdown) // 💡 open filters inside modal for modern look
            ->filtersTriggerAction(fn ($action) => $action
                ->button()
                ->label('Filter')
                ->icon('heroicon-o-funnel')
                ->color('success') // green button
            )
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAreas::route('/'),
            'create' => Pages\CreateArea::route('/create'),
            'edit' => Pages\EditArea::route('/{record}/edit'),
        ];
    }
}
