<?php

namespace App\Http\Controllers;

use App\Imports\InstrumentIndicesImport;
use App\Imports\ServiceImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class ImportController extends Controller
{
    public function index(){
        return view('import.index');
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new InstrumentIndicesImport, $request->file('file'));

        return back()->with('success', 'Data imported successfully!');
    }

    public function indexService(){
        return view('import.service');
    }

    public function importService(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ServiceImport, $request->file('file'));

        return back()->with('success', 'Data imported successfully!');
    }
}
