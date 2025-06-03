<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstrumentIndexResource\Pages;
use App\Filament\Resources\InstrumentIndexResource\RelationManagers;
use App\Models\DevModel;
use App\Models\InstrumentIndex;
use App\Models\LoopNumberRequest;
use App\Models\Setting;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\InstrumentIndexService;
use Illuminate\Support\Facades\Mail;

class InstrumentIndexResource extends Resource
{
    protected static ?string $model = InstrumentIndex::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('dev')
                    ->label('Device Code')
                    ->options(function () {
                        return \App\Models\DevModel::pluck('code', 'code')->toArray();
                        // key = code, value = code
                    })
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get, $state) {
                        // Auto-generate loop number (code field)
                        $generatedCode = app(\App\Services\InstrumentIndexService::class)->generateLoopNo(
                            $state,
                            $get('area_id'),
                            $get('service_id')
                        );
                        $set('code', $generatedCode);

                        // Auto-populate device description
                        $dev = \App\Models\DevModel::where('code', $state)->first();
                        $set('device_description', $dev?->description ?? '');
                    }),

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
                Select::make('manufacturer')->options(function () {
                    return Setting::where('setting_type','MANUFACTURER')->pluck('setting_value', 'setting_name')->toArray();
                }),
                TextInput::make('model')->label('Model'),
                TextInput::make('range_unit')->label('Range Unit'),
                Select::make('outsignal')->options(function () {
                    return Setting::where('setting_type','OUTSIGNAL')->pluck('setting_value', 'setting_name')->toArray();
                }),
                Select::make('supply')->options(function () {
                    return Setting::where('setting_type','SUPPLY')->pluck('setting_value', 'setting_name')->toArray();
                }),
                TextInput::make('loop_drwg')->label('Loop Drawing'),
                TextInput::make('spec_no')->label('Spec No'),
                TextInput::make('pr_mr_no')->label('PR / MR No'),
                Textarea::make('remark')->label('remark')->columnSpanFull(),
                Radio::make('status_updated')->label('Status')->options([
                    'pending' => 'Pending',
                    'approve' => 'Approve',
                    'reject' => 'Reject',
                ])->reactive(),
                TextArea::make('remark_updated')->label('Reason For Rejected')->columnSpanFull()
                    ->required(fn ($get) => $get('status_updated') === 'reject')
                    ->visible(fn ($get) => $get('status_updated') === 'reject'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('areas.name')->label('Area')->hidden(),
                Tables\Columns\TextColumn::make('dev'),
                Tables\Columns\TextColumn::make('code')->label('Loop No'),
                Tables\Columns\TextColumn::make('services.name')->label('Service'),
                Tables\Columns\TextColumn::make('pid_drawing')->label('P&ID Drawing'),
                Tables\Columns\TextColumn::make('device_description')->label('Device Description'),
                Tables\Columns\TextColumn::make('manufacturer')->label('Manufacturer'),
                Tables\Columns\TextColumn::make('model')->label('Model/Element Type'),
                Tables\Columns\TextColumn::make('range_unit')->label('Range Unit'),
                Tables\Columns\TextColumn::make('outsignal')->label('Outsignal'),
                Tables\Columns\TextColumn::make('supply')->label('Supply'),
                Tables\Columns\TextColumn::make('loop_drwg')->label('Loop Drawing'),
                Tables\Columns\TextColumn::make('spec_no')->label('Spec No'),
                Tables\Columns\TextColumn::make('po_mr_no')->label('PR / MR No'),
                Tables\Columns\TextColumn::make('remark')->label('Remark')->inline()->disableClick(),  // prevent navigation on row click// remove or limit actions if needed
                Tables\Columns\TextColumn::make('status_updated')->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_updated')->options([
                    'pending' => 'Pending',
                    'approve' => 'Approve',
                    'reject' => 'Reject',
                ])->label('Status'),
                Tables\Filters\Filter::make('ticket_number')
                    ->form([
                        TextInput::make('value')
                            ->label('Search Ticket Number'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['value'],
                                fn ($query, $value) => $query->where('ticket_number', 'like', "%{$value}%")
                            );
                    }),
                Tables\Filters\SelectFilter::make('area_id')->options(\App\Models\Area::where('type', 'SUB_AREA')->pluck('name', 'id'))->label('Area'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Select::make('status_updated')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approve' => 'Approve',
                                'reject' => 'Reject',
                            ])
                            ->required()
                            ->reactive(),

                        Textarea::make('remark_updated')
                            ->label('Reason for Rejection')
                            ->requiredIf('status_updated', 'reject')
                            ->visible(fn ($get) => $get('status_updated') === 'reject'),
                    ])->action(function ($record, array $data) {
                        $record->update([
                            'status_updated' => $data['status_updated'],
                            'remark_updated' => $data['remark_updated'] ?? null,
                        ]);

                        $loopNumberRequest = LoopNumberRequest::with('engineers')->where('id', $record->loop_number_requests_id)->first();
                        if (in_array($data['status_updated'], ['approve', 'reject'])) {
                            Mail::to($loopNumberRequest->engineers?->email) // replace with dynamic email if needed
                            ->send(new \App\Mail\StatusUpdatedMail($record, $loopNumberRequest));
                        }

                    })
                    ->modalHeading('Update Status')
                    ->modalSubheading('Rejecting requires a reason.')
                    ->requiresConfirmation(true),
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
