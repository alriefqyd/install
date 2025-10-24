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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class InstrumentIndexResource extends Resource
{

    protected static ?string $model = InstrumentIndex::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    public static function getNavigationLabel(): string
    {
        return 'Instrument Index'; // or whatever label you want
    }
    public static function getBreadcrumb(): string
    {
        return 'Instrument Index'; // your new breadcrumb text
    }
    public static function getTableQuery(): Builder
    {
        return InstrumentIndex::query()
            ->select('ticket_number')
            ->distinct();
    }
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

    /** public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('ticket_number')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')->label('Ticket Number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('dev')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->label('Loop No')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('services.name')->label('Service')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('pid_drawing')->label('P&ID Drawing')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('device_description')->label('Device Description'),
                Tables\Columns\TextColumn::make('manufacturer')->label('Manufacturer')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('model')->label('Model/Element Type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('range_unit')->label('Range Unit')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('outsignal')->label('Outsignal')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('supply')->label('Supply')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('loop_drwg')->label('Loop Drawing')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('spec_no')->label('Spec No')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('po_mr_no')->label('PR / MR No')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('remark')->label('Remark')->inline()->disableClick(),  // prevent navigation on row click// remove or limit actions if needed
                Tables\Columns\TextColumn::make('status_updated')->label('Status')->sortable(),
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
    } **/
    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->selectRaw('MIN(id) as id, ticket_number, MAX(is_finalize) as is_finalize, MAX(created_at) as created_at')
                    ->groupBy('ticket_number')
                    ->orderByDesc('created_at');
            })
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('Ticket Number')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => $state ?: 'Empty')
                    ->url(fn ($record) => $record->ticket_number
                        ? url("/ticket/{$record->ticket_number}")
                        : null),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d-M-Y H:i A'),

                Tables\Columns\TextColumn::make('is_finalize')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Finalized',
                        0 => 'Not Finalized',
                        default => '-',
                    })
                    ->badge()
                    ->colors([
                        'warning' => fn ($state) => $state == 0,
                        'success' => fn ($state) => $state == 1,
                    ]),
            ])->filters([
                Tables\Filters\SelectFilter::make('is_finalize')
                    ->label('Finalization Status')
                    ->options([
                        1 => 'Finalized',
                        0 => 'Not Finalized',
                    ])
                    ->indicator('Finalization Status'),
            ]);

        /** return $table
            ->modifyQueryUsing(function ($query) {
                // Remove whereNotNull() so all data appears
                $query->orderByDesc('created_at');
            })
            ->defaultGroup('ticket_number')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('Ticket Number')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => $state ?: '-'), // 👈 Show '-' if null or empty
                Tables\Columns\TextColumn::make('dev')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->label('Loop No')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('services.name')->label('Service')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('pid_drawing')->label('P&ID Drawing')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('device_description')->label('Device Description'),
                Tables\Columns\TextColumn::make('manufacturer')->label('Manufacturer')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('model')->label('Model/Element Type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('range_unit')->label('Range Unit')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('outsignal')->label('Outsignal')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('supply')->label('Supply')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('loop_drwg')->label('Loop Drawing')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('spec_no')->label('Spec No')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('po_mr_no')->label('PR / MR No')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('remark')->label('Remark')->inline()->disableClick(),
                Tables\Columns\TextColumn::make('status_updated')->label('Status')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_updated')->options([
                    'pending' => 'Pending',
                    'approve' => 'Approve',
                    'reject' => 'Reject',
                ])->label('Status'),

                Tables\Filters\Filter::make('ticket_number')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Search Ticket Number'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'],
                            fn($query, $value) => $query->where('ticket_number', 'like', "%{$value}%")
                        );
                    }),

                Tables\Filters\SelectFilter::make('area_id')
                    ->options(\App\Models\Area::where('type', 'SUB_AREA')->pluck('name', 'id'))
                    ->label('Area'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        \Filament\Forms\Components\Select::make('status_updated')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approve' => 'Approve',
                                'reject' => 'Reject',
                            ])
                            ->required()
                            ->reactive(),

                        \Filament\Forms\Components\Textarea::make('remark_updated')
                            ->label('Reason for Rejection')
                            ->requiredIf('status_updated', 'reject')
                            ->visible(fn($get) => $get('status_updated') === 'reject'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status_updated' => $data['status_updated'],
                            'remark_updated' => $data['remark_updated'] ?? null,
                        ]);

                        $loopNumberRequest = \App\Models\LoopNumberRequest::with('engineers')
                            ->where('id', $record->loop_number_requests_id)
                            ->first();

                        if (in_array($data['status_updated'], ['approve', 'reject'])) {
                            Mail::to($loopNumberRequest->engineers?->email)
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
         * **/
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
            'ticket' => Pages\ViewInstrumentIndexByTicket::route('/ticket/{ticket_number}'),
        ];
    }
}
