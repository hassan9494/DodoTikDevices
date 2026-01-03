<?php

namespace App\Http\Controllers;

use App\Exports\FileParametersExport;
use App\Exports\ParametersDataExport;
use App\Models\Device;
use App\Models\FilesParametersValues;
use App\Models\FtpFile;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        return view('admin.ftp_files.index', compact('files'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $files = File::files(public_path('ftpfiles'));

        $allFiles = [];
        foreach ($files as $file) {
            $newFile = FtpFile::where('name', $file->getFilenameWithoutExtension())->first();
            if ($newFile == null) {
                $newFile = new FtpFile();
                $newFile->name = $file->getFilenameWithoutExtension();
                $newFile->extension = $file->getExtension();
                $newFile->save();
            }
            $fileContents = File::get($file);
            $lines = explode("\n", $fileContents);


            $filecontent = [];
            if (count($lines) == 1) {
                foreach ($lines as $line) {
                    $replacedString = str_replace("\r", "|", $line);
                    $array = explode("|", $replacedString);
                    $firstline = explode(",", trim($array[0], " "));
                    $firstline = array_map('trim', $firstline);

                    $replacedFirstline = str_replace("YYYY-MM-DD hh:mm:ss", "date", $firstline);
                    $isFirstLine = true;
                    foreach ($array as $key => $data) {
                        if ($isFirstLine) {
                            $isFirstLine = false;
                            continue; // Skip the first line
                        }
                        $line = explode(",", $data);
                        if (count($line) == count($replacedFirstline)) {
                            $replacedArray = array_combine($replacedFirstline, array_values($line));
                            $newParameters = new FilesParametersValues();
                            $newParameters->file_id = $newFile->id;
                            $newParameters->parameters = $replacedArray;
                            $newParameters->time_of_read = $replacedArray['date'];
                            $newParameters->save();
                            array_push($filecontent, $replacedArray);
                        }
                    }
                }
                $oldFilePath = public_path('oldFtpFiles') . '/' . $file->getFilename();
                File::move($file, $oldFilePath);
                array_push($allFiles, $filecontent);
            } else {
                $firstline = explode(",", trim($lines[0], " "));
                $firstline = array_map('trim', $firstline);
                $format = 'j/n/Y H:i';
                $replacedFirstline = str_replace("YYYY-MM-DD hh:mm:ss", "date", $firstline);
                $isFirstLine = true;
                foreach ($lines as $key => $data) {
                    if ($isFirstLine) {
                        $isFirstLine = false;
                        continue; // Skip the first line
                    }
                    $line = explode(",", $data);
                    if (count($line) == count($replacedFirstline)) {
                        $replacedArray = array_combine($replacedFirstline, array_values($line));

                        if ($replacedArray['Flow'] != ""){
                            $newParameters = new FilesParametersValues();
                            $newParameters->file_id = $newFile->id;
                            $newParameters->parameters = $replacedArray;
                            $dateTime = DateTime::createFromFormat($format, $replacedArray['date']);
                            if ($dateTime != false){
                                $formattedDateTime = $dateTime->format('Y-m-d H:i:s');
                            }else{
                                $formattedDateTime =$replacedArray['date'];
                            }
                            $newParameters->time_of_read = $formattedDateTime;
                            $newParameters->save();
                            array_push($filecontent, $replacedArray);
                        }

                    }
                }
                $oldFilePath = public_path('oldFtpFiles') . '/' . $file->getFilename();
                File::move($file, $oldFilePath);
                array_push($allFiles, $filecontent);
            }

        }
        $files = FtpFile::all();
        return view('admin.ftp_files.index', compact('files'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ftpFile = FtpFile::findOrFail($id);
        $parametersvalues = $ftpFile->fileParameters()->whereDate('time_of_read', '>=', Carbon::yesterday())->get();
        $paraValues = [];
        $yValues = [];
        $xValues = [];
        $now = Carbon::now();
        $thisMidnight = Carbon::now()->endOfDay();
        $parameters = ['Flow', 'TOT1', 'TOT2', 'TOT3'];
        foreach ($parameters as $key => $value) {
            $xValues = [];
            foreach ($parametersvalues as $parametersvalue) {
                if (($now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parametersvalue->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0) || $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0) {
                    array_push($yValues, json_decode($parametersvalue->parameters, true)[$value]);
                    array_push($xValues, date(DATE_ISO8601, strtotime($parametersvalue->time_of_read)));
                }

            }
            array_push($paraValues, $yValues);
            $yValues = [];
        }

        $label = 1;
        return view('admin.ftp_files.show', compact('ftpFile', 'label', 'parameters', 'paraValues', 'xValues'));
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
        $parameters = ['Flow', 'TOT1', 'TOT2', 'TOT3'];
        foreach ($parameters as $key => $value) {
            $xValues = [];

            if (($from == 1 && $to == 0) || ($from == -1 && $to == -1)) {
                foreach ($parametersvalues as $parametersvalue) {
                    if (($now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d == 1 && $thisMidnight->diff(date("m/d/Y H:I", strtotime($parametersvalue->time_of_read)))->h <= 2 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0) || $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0) {
                        array_push($yValues, json_decode($parametersvalue->parameters, true)[$value]);
                        array_push($xValues, date(DATE_ISO8601, strtotime($parametersvalue->time_of_read)));
                    }
                }
            } else {
                foreach ($parametersvalues as $parametersvalue) {
                    if ($now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d <= $from && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->d >= $to && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->m == 0 && $now->diff(date("m/d/Y", strtotime($parametersvalue->time_of_read)))->y == 0) {
                        array_push($yValues, json_decode($parametersvalue->parameters, true)[$value]);
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
        $validated = $request->validate([
            'from' => ['required'],
            'to' => ['required'],
            'id' => ['required', 'integer', 'exists:ftp_files,id'],
        ]);

        $from = $validated['from'];
        $to = $validated['to'];
        $file = $validated['id'];
        return Excel::download(new FileParametersExport($from, $to, $file), 'parameter.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
