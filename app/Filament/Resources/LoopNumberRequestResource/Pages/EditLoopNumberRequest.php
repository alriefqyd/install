<?php

namespace App\Filament\Resources\LoopNumberRequestResource\Pages;

use App\Filament\Resources\LoopNumberRequestResource;
use App\Mail\SendApprovedLoopNumberMail;
use App\Mail\SendRejectLoopNumberMail;
use App\Models\Engineers;
use App\Models\InstrumentIndex;
use App\Models\LoopNumberRequest;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Date;
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
                $now = Carbon::now();
                $count = LoopNumberRequest::whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month)
                    ->count();
                $dayMonth = $now->format('dm'); // e.g., 2505 for May 25
                $runningNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT); // 01, 02, ...
                $generatedCode = $dayMonth . $runningNumber;
//
                if(sizeof($record->loop_number) > 0){
                    foreach ($record->loop_number as $loop_number) {
//                        $deletedItem = InstrumentIndex::where('code', $loop_number['loop_number'])->where('loop_number_request_id', $record->id)->first();
//                        if($deletedItem){
//                            $deletedItem->delete();
//                        }
//
                        $record->session_id = $session_id;
                        $record->ticket_number = $generatedCode;
                        $record->save();
                    }
                }

                Mail::to($requestor->email)->send(new SendApprovedLoopNumberMail($record,$requestor, $session_id, $generatedCode));
            }

            if($record->status == 'reject'){
                Mail::to($requestor->email)->send(new SendRejectLoopNumberMail($record,$requestor));
            }

        }catch (\Exception $exception){
           echo $exception->getMessage();
        }

    }
}
