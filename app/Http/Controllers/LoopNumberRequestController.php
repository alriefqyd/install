<?php

namespace App\Http\Controllers;

use App\Mail\SendSubmissionNotificationMail;
use App\Mail\SendUpdateNotificationEmail;
use App\Mail\SubmissionNotificationEmail;
use App\Models\Area;
use App\Models\DevModel;
use App\Models\Engineers;
use App\Models\InstrumentIndex;
use App\Models\LoopNumberRequest;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;
use Psy\Util\Json;

class LoopNumberRequestController extends Controller
{
    public function index(){
        $engineers = Engineers::all();
        return view('LoopNumber.RequestForm', [
            'engineers' => $engineers,
        ]);
    }
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png','pdf','JPG','JPEG','PNG'];
    public function requestLoop(Request $request){
        DB::beginTransaction();

        try {
            $p_and_id = $this->uploadDocument($request, $request->p_and_id, self::ALLOWED_EXTENSIONS);

            $hmi = $request->hasFile('hmi') ? $this->uploadDocument($request, $request->hmi, self::ALLOWED_EXTENSIONS) : null;

            $requstLoop = new LoopNumberRequest();
            $requstLoop->engineers_id = $request->engineer;
            $requstLoop->p_and_id_document = $p_and_id;
            $requstLoop->hmi_document = $hmi;
            $requstLoop->area_id = Area::where('name', Setting::area[$request->area])->first()->id;

            $requstLoop->save();

            $this->sendEmailSubmitNotification($requstLoop);
            DB::commit();
            return redirect('request-loop-no/success');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
            foreach ($request->devices as $device) {
                $instrumentIndex = new InstrumentIndex();
                if(isset($device['instrument'])){
                    $instrumentIndex = InstrumentIndex::where('id', $device['instrument'])->first();
                }
                $instrumentIndex->pid_drawing = $request->pid_drawing;
                $instrumentIndex->loop_number = $request->loop_no;
                $instrumentIndex->service_id = $request->service_id;
                $instrumentIndex->area_id = $request->area;
                $instrumentIndex->device_description = $device['device_descrp'];
                $instrumentIndex->manufacturer = $device['manufacturer'];
                $instrumentIndex->model = $device['model_type'];
                $instrumentIndex->range_unit = $device['range_unit'];
                $instrumentIndex->outsignal = $device['outsignl'];
                $instrumentIndex->loop_drwg = $device['loop_dwg'];
                $instrumentIndex->spec_no = $device['spec_no'];
                $instrumentIndex->po_mr_no = $device['po_mr_no'];
                $instrumentIndex->remark = $device['remark'];
                $instrumentIndex->supply = $device['supply'];
                $instrumentIndex->dev = $device['dev'];
                $instrumentIndex->code = $request->loop_no;
                $instrumentIndex->loop_number_requests_id = $request->id;
                $instrumentIndex->ticket_number = $request->ticket_number;
                $instrumentIndex->save();
            }

            //$this->sendEmailUpdateNotification($instrumentIndex);
            DB::commit();
            return response()->json(['success' => 'Successfully updated'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function edit(Request $request)
    {
        $loopNumberRequest = LoopNumberRequest::with(['areas','instruments'])->where('session_id', $request->sessionId)->first();
        $loopNumbers = $loopNumberRequest->loop_number;
        $service = Service::all();
        $dev = DevModel::all();
        $area = Area::where('type','AREA')->get();
        $subArea = Area::where('type','SUB_AREA')->get();
        return view('LoopNumber.editForm', [
            'instrumentIndex' => $loopNumberRequest,
            'loopNumbers' => $loopNumbers,
            'services' => $service,
            'dev' => $dev,
            'area' => $area,
            'subArea' => $subArea,
        ]);
    }

    public function finalize(Request $request){
        try{
            DB::beginTransaction();
            $instruments = InstrumentIndex::where('ticket_number', $request->ticket)->get();
            foreach ($instruments as $instrument) {
                $instrument->is_finalize = 1;
                $instrument->save();
            }
            $this->sendEmailUpdateNotification($instruments[0]);
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadDocument(Request $request,$file, $allowedFileExtension){
        $document_name = null;
        $dir = null;
        if($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedFileExtension);
            if($check){
                $document_name = $filename . '_' . uniqid() . '.' . $extension;
                $dir = 'documents/requests/';
                Storage::disk('public')->putFileAs($dir, $file, $document_name);
            }
        }
        return $dir . $document_name;
    }

    public function success()
    {
        return view('LoopNumber.success');
    }

    private function deleteDocument($document, $path){
        $existDocumentName = storage_path('app/documents/') . $path .'/' . $document;
        if (File::exists($existDocumentName)) {
            File::delete($existDocumentName);
        }
    }

    public function getDataDev()
    {
        $data = DevModel::all();
        return response()->json($data);
    }

    private function sendEmailSubmitNotification(LoopNumberRequest $requstLoop){
        if(isset($requstLoop->id)){
            try{
                Mail::to("fathur.miftahudin@vale.com")->send(new SendSubmissionNotificationMail($requstLoop));
                Log::info('Email send to : fathur');
            } catch (Exception $e){
                Log::error($e->getMessage());
            }
        } else {
            Log::warning("No email found");
        }
    }

    private function sendEmailUpdateNotification(InstrumentIndex $instrumentIndex){
        if(isset($instrumentIndex->id)){
            try{
                Mail::to("fathur.miftahudin@vale.com")->send(new SendUpdateNotificationEmail($instrumentIndex));
                Log::info('Email send to : fathur.miftahudin@vale.com');
            } catch (Exception $e){
                Log::error($e->getMessage());
            }
        } else {
            Log::warning("No email found");
        }
    }

}
