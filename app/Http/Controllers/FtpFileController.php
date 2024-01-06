<?php

namespace App\Http\Controllers;

use App\Exports\FileParametersExport;
use App\Exports\ParametersDataExport;
use App\Models\Device;
use App\Models\FtpFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FtpFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $files = FtpFile::all();
        return view('admin.ftp_files.index',compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ftpFile = FtpFile::findOrFail($id);
        $parametersvalues = $ftpFile->fileParameters()->whereDate('time_of_read','>=', Carbon::yesterday())->get();
        $paraValues = [];
        $yValues = [];
        $xValues = [];
        $now = Carbon::now();
        $thisMidnight = Carbon::now()->endOfDay();
        $parameters = ['Flow','TOT1','TOT2','TOT3'];
        foreach ($parameters as $key => $value){
            $xValues = [];
            foreach ($parametersvalues as $parametersvalue){
                if (($now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parametersvalue->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0) || $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0){
                    array_push($yValues,json_decode($parametersvalue->parameters,true)[$value]);
                    array_push($xValues, date(DATE_ISO8601, strtotime($parametersvalue->time_of_read)));
                }

            }
            array_push($paraValues, $yValues);
            $yValues = [];
        }

        $label = 1;
        return view('admin.ftp_files.show', compact('ftpFile','label','parameters','paraValues','xValues'));
    }

    public function showWithDate($id, $from, $to)
    {
        $ftpFile = FtpFile::findOrFail($id);
        $parametersvalues = $ftpFile->fileParameters;
        $paraValues = [];
        $yValues = [];
        $xValues = [];
        $now = Carbon::now();
        $thisMidnight = Carbon::now()->endOfDay();
        $parameters = ['Flow','TOT1','TOT2','TOT3'];
        foreach ($parameters as $key => $value){
            $xValues = [];

            if (($from == 1 && $to == 0) || ($from == -1 && $to == -1) ) {
                foreach ($parametersvalues as $parametersvalue) {
                    if (($now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parametersvalue->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0) || $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0){
                        array_push($yValues,json_decode($parametersvalue->parameters,true)[$value]);
                        array_push($xValues, date(DATE_ISO8601, strtotime($parametersvalue->time_of_read)));
                    }
                }
            }else{
                foreach ($parametersvalues as $parametersvalue){
                    if ($now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d <= $from && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d >= $to && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0) {
                        array_push($yValues,json_decode($parametersvalue->parameters,true)[$value]);
                        array_push($xValues, date(DATE_ISO8601, strtotime($parametersvalue->time_of_read)));
                    }
                }
            }
            array_push($paraValues, $yValues);
            $yValues = [];
        }

        $label = 1;
        return array($paraValues, $xValues, $label);
    }

    public function export($id)
    {
        $ftpFile = FtpFile::findOrFail($id);
        return view('admin.ftp_files.export', compact('ftpFile'));
    }

    public function exportToDatasheet(Request $request)
    {
        $validate = [
            'from' => 'required',
            'to' => 'required'
        ];
        \Validator::make($request->all(), $validate)->validate();
        $from = $request->from;
        $to = $request->to;
        $file = $request->id;
        return Excel::download(new FileParametersExport($from, $to, $file), 'parameter.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
