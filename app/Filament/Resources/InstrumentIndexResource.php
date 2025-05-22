<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstrumentIndexResource\Pages;
use App\Filament\Resources\InstrumentIndexResource\RelationManagers;
use App\Models\InstrumentIndex;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\InstrumentIndexService;

class InstrumentIndexResource extends Resource
{
    protected static ?string $model = InstrumentIndex::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Select::make('dev')->options(function () {
//                    return Setting::where('setting_type', 'DEV_CODE')->pluck('setting_name', 'setting_value')->toArray();
//                })->reactive()
//                    ->afterStateUpdated(function (callable $set, callable $get) {
//                        $code = app(InstrumentIndexService::class)->generateLoopNo(
//                            $get('dev'),
//                            $get('area_id'),
//                            $get('service_id')
//                        );
//                        $set('code', $code);
//                    }),
                Select::make('area_id')
                    ->label('Area')
                    ->options(\App\Models\Area::where('type', 'SUB_AREA')->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $code = app(InstrumentIndexService::class)->generateLoopNo(
                            $get('dev'),
                            $get('area_id'),
                            $get('service_id')
                        );
                        $set('code', $code);
                    }),

                Select::make('service_id')
                    ->label('Service')
                    ->options(function (callable $get) {
                        $areaId = $get('area_id');
                        return $areaId
                            ? \App\Models\Service::pluck('name', 'id')
                            : [];
                    })
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $code = app(InstrumentIndexService::class)->generateLoopNo(
                            $get('dev'),
                            $get('area_id'),
                            $get('service_id')
                        );
                        $set('code', $code);
                    }),

                TextInput::make('code')
                    ->label('Code'),
                TextInput::make('pid_drawing')->label('P&ID Drawing'),
                TextInput::make('device_description')->label('Device Description'),
                TextInput::make('manufacturer')->label('Manufacturer'),
                TextInput::make('model')->label('Model'),
                TextInput::make('range_unit')->label('Range Unit'),
                TextInput::make('outsignal')->label('Outsignal'),
                TextInput::make('loop_drwg')->label('Loop Drawing'),
                TextInput::make('spec_no')->label('Spec No'),
                TextInput::make('pr_mr_no')->label('PR / MR No'),
                TextInput::make('remark')->label('remark'),
                TextInput::make('supply')->label('supply'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('pid_drawing'),
                Tables\Columns\TextColumn::make('device_description'),
                Tables\Columns\TextColumn::make('manufacturer'),
                Tables\Columns\TextColumn::make('model'),
                Tables\Columns\TextColumn::make('range_unit'),
                Tables\Columns\TextColumn::make('outsignal'),
                Tables\Columns\TextColumn::make('loop_drwg'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListInstrumentIndices::route('/'),
            'create' => Pages\CreateInstrumentIndex::route('/create'),
            'edit' => Pages\EditInstrumentIndex::route('/{record}/edit'),
        ];
    }
}
