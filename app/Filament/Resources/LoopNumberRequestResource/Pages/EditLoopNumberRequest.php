<?php

namespace App\Filament\Resources\LoopNumberRequestResource\Pages;

use App\Filament\Resources\LoopNumberRequestResource;
use App\Mail\SendApprovedLoopNumberMail;
use App\Mail\SendRejectLoopNumberMail;
use App\Models\Engineers;
use App\Models\InstrumentIndex;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class EditLoopNumberRequest extends EditRecord
{
    protected static string $resource = LoopNumberRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        try {
            $requestor = Engineers::where('id', $record->engineers_id)->first();
            $session_id = Str::uuid()->toString();

            if($record->status == 'approve'){
                if(sizeof($record->loop_number) > 0){
                    foreach ($record->loop_number as $loop_number) {
                        $deletedItem = InstrumentIndex::where('code', $loop_number['loop_number'])->where('loop_number_request_id', $record->id)->first();
                        if($deletedItem){
                            $deletedItem->delete();
                        }
                        InstrumentIndex::create([
                            'code' => $loop_number['loop_number'],
                            'dev' => $record->dev,
                            'service_id' => $record->services_id,
                            'area_id' => $record->area_id,
                            'loop_number_request_id' => $record->id,
                            'session_id' => $session_id
                        ]);
                    }
                }

                Mail::to($requestor->email)->send(new SendApprovedLoopNumberMail($record,$requestor, $session_id));
            }

        }catch (\Exception $exception){
           echo $exception->getMessage();
        }

        if($record->status == 'reject'){
            Mail::to($requestor->email)->send(new SendRejectLoopNumberMail($record,$requestor));
        }
    }
}
