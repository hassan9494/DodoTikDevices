<?php

namespace App\Exports;

use App\Models\FtpFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FileParametersExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;
    protected $file;

    function __construct($from, $to, $file)
    {
        $this->from = $from;
        $this->to = $to;
        $this->file = $file;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $ftpFile = FtpFile::findOrFail($this->file);
        $fileParameters = $ftpFile->fileParameters()->select( 'parameters', 'time_of_read')->whereBetween('time_of_read', [date('Y-m-d', strtotime($this->from)) . " 00:00:00", date('Y-m-d', strtotime($this->to)) . " 23:59:59"])
            ->get();
        $parameters = ['Flow','TOT1','TOT2','TOT3'];
        foreach ($fileParameters as $fileParameter){
            foreach ($parameters as $key => $parameter) {
                $x = $parameter;
                if (isset(json_decode($fileParameter->parameters, true)[$parameter])){
                    $fileParameter[$x] = json_decode($fileParameter->parameters, true)[$parameter];
                }else if (isset(json_decode($fileParameter->parameters, true)[$parameter])){
                    $fileParameter[$x] = json_decode($fileParameter->parameters, true)[$parameter];
                }else{
                    $fileParameter[$x] = "-";
                }

            }
            unset($fileParameter->parameters);
            $fileParameter->file = $ftpFile->name;
            $fileParameter->time_of_read = Carbon::parse($fileParameter->time_of_read)->setTimezone('Europe/Istanbul')->format('Y-m-d H:i');
        }
        return $fileParameters;
    }

    public function headings(): array
    {
        $ftpFile = FtpFile::findOrFail($this->file);
        $headers = [
            'time of read',

        ];
        $parameters = ['Flow','TOT1','TOT2','TOT3'];
        foreach ($parameters as $type) {
            array_push($headers, $type);
        }
        array_push($headers,'File');
        return $headers;
    }
}
