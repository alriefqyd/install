<?php

namespace App\Http\Controllers;

use App\Mail\SendSubmissionNotificationMail;
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

class LoopNumberRequestController extends Controller
{
    public function index(){
        $engineers = Engineers::all();
        return view('loopNumber.requestForm', [
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
            $instrumentIndex = InstrumentIndex::where('id', $request->id)->first();
            $instrumentIndex->pid_drawing = $request->pid_drawing;
            $instrumentIndex->device_description = $request->device_description;
            $instrumentIndex->manufacturer = $request->manufacturer;
            $instrumentIndex->model = $request->model;
            $instrumentIndex->range_unit = $request->range_unit;
            $instrumentIndex->outsignal = $request->outsignal;
            $instrumentIndex->loop_drwg = $request->loop_drwg;
            $instrumentIndex->spec_no = $request->spec_no;
            $instrumentIndex->po_mr_no = $request->po_mr_no;
            $instrumentIndex->remark = $request->remark;
            $instrumentIndex->supply = $request->supply;
            $instrumentIndex->dev = $request->dev;
            $instrumentIndex->save();
            DB::commit();
            return response()->json(['success' => 'Successfully updated'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function edit(Request $request)
    {
        $instrumentIndex = InstrumentIndex::where('session_id', $request->sessionId)->get();
        $service = Service::all();
        $dev = DevModel::all();
        return view('loopNumber.editForm', [
            'instrumentIndex' => $instrumentIndex,
            'services' => $service,
            'dev' => $dev
        ]);
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
        return view('loopNumber.success');
    }

    private function deleteDocument($document, $path){
        $existDocumentName = storage_path('app/documents/') . $path .'/' . $document;
        if (File::exists($existDocumentName)) {
            File::delete($existDocumentName);
        }
    }

    private function sendEmailSubmitNotification(LoopNumberRequest $requstLoop){
        if(isset($requstLoop->id)){
            try{
                Mail::to("c0661472@vale.com")->send(new SendSubmissionNotificationMail($requstLoop));
                Log::info('Email send to : elfriani@vale.com');
            } catch (Exception $e){
                Log::error($e->getMessage());
            }
        } else {
            Log::warning("No email found");
        }
    }
}
