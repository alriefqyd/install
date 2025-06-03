<?php

namespace App\Http\Controllers;

use App\Imports\InstrumentIndicesImport;
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
}
