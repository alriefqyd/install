<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoopNumberRequestResource\Pages;
use App\Filament\Resources\LoopNumberRequestResource\RelationManagers;
use App\Models\Area;
use App\Models\DevModel;
use App\Models\Engineers;
use App\Models\LoopNumberRequest;
use App\Models\Service;
use Faker\Core\File;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class LoopNumberRequestResource extends Resource
{
    protected static ?string $model = LoopNumberRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('area_id')->options(function (){
                return Area::where('type','AREA')->pluck('name', 'id');
            }),
            Forms\Components\Select::make('engineers_id')
                ->label('Engineer')
                ->options(function () {
                    return Engineers::all()->pluck('name', 'id');
                })->searchable()->columnSpan('full'),
            Forms\Components\FileUpload::make('p_and_id_document')
                ->disk('public')
                ->directory('documents/requests')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // name only, no extension
                    $extension = $file->getClientOriginalExtension(); // extension only

                    return $originalName . '_' . uniqid() . '.' . $extension;
                }),
            Forms\Components\FileUpload::make('hmi_document')
                ->disk('public')
                ->directory('documents/requests')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // name only, no extension
                    $extension = $file->getClientOriginalExtension(); // extension only

                    return $originalName . '_' . uniqid() . '.' . $extension;
                }),
            Forms\Components\Placeholder::make('p_and_id_document_link')
                ->label('P&ID Document')
                ->content(function ($get, $set, ?\App\Models\LoopNumberRequest $record) {
                    if ($record && $record->p_and_id_document) {
                        $url = asset('storage/' . $record->p_and_id_document);
                        return new HtmlString('<a href="' . $url . '" target="_blank" rel="noopener noreferrer">Download current file</a>');
                    }
                    return 'No file uploaded';
                }),
            Forms\Components\Placeholder::make('hmi_document_link')
                ->label('Download HMI Document')
                ->content(function ($get, $set, ?\App\Models\LoopNumberRequest $record) {
                    if ($record && $record->hmi_document) {
                        $url = asset('storage/' . $record->hmi_document);
                        return new HtmlString('<a href="' . $url . '" target="_blank" rel="noopener noreferrer">Download current file</a>');
                    }
                    return 'No file uploaded';
                }),
            Forms\Components\Radio::make('status')->options([
                'approve' => 'Approve',
                'reject' => 'Reject',
                'pending' => 'Pending'
            ])->label('Status')->reactive(),
            Forms\Components\Select::make('remarks')->columnSpan('full')
                ->label('Reason for Rejection')
                ->options([
                    'Upload the P&ID drawing' => 'Upload the P&ID drawing',
                    'Mark the instruments on the P&ID drawing and re-upload' => 'Mark the instruments on the P&ID drawing and re-upload',
                    'Mark and upload the HMI screenshot' => 'Mark and upload the HMI screenshot'
                ])->searchable()->multiple()
                ->required(fn ($get) => $get('status') === 'reject')
                ->visible(fn ($get) => $get('status') === 'reject'),
            Forms\Components\Select::make('services_id')->options(function () {
                return Service::all()->pluck('name', 'id');
            })->searchable()->columnSpan('full')
                ->required(fn ($get) => $get('status') === 'approve')
                ->visible(fn ($get) => $get('status') === 'approve'),
            Forms\Components\Repeater::make('loop_number')
                ->schema([
                    TextInput::make('loop_number')->required(),
                ])->defaultItems(2)->columnSpan('full')
                ->required(fn ($get) => $get('status') === 'approve')
                ->visible(fn ($get) => $get('status') === 'approve')
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('engineers.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('areas.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at'),
                Tables\Columns\IconColumn::make('status')
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-pencil',
                        'reject' => 'heroicon-o-clock',
                        'approve' => 'heroicon-o-check-circle',
                    })->color(fn (string $state): string => match ($state) {
                        'pending' => 'info',
                        'reject' => 'warning',
                        'approve' => 'success',
                        default => 'gray',
                    })->sortable(),
                TextColumn::make('hmi_document')->sortable()->searchable()->alignCenter()
                    ->formatStateUsing(function ($state) {
                        return $state
                            ? '<div class="w-full text-center"><a href="' . asset('storage/' . $state) . '" target="_blank" class="underline">Download</a></div>'
                            : '<div class="w-full text-center">No File</div>';
                    })
                    ->html(),
                TextColumn::make('p_and_id_document')
                    ->alignCenter()
                    ->formatStateUsing(function ($state) {
                        return $state
                            ? '<div class="w-full text-center"><a href="' . asset('storage/' . $state) . '" target="_blank" class="underline">Download</a></div>'
                            : '<div class="w-full text-center">No File</div>';
                    })
                    ->html()
                    ->label('P&ID Document'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approve' => 'Approve',
                        'reject' => 'Reject',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLoopNumberRequests::route('/'),
            'create' => Pages\CreateLoopNumberRequest::route('/create'),
            'edit' => Pages\EditLoopNumberRequest::route('/{record}/edit'),
        ];
    }
}
