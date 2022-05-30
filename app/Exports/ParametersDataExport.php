<?php

namespace App\Exports;

use App\Models\Device;
use App\Models\DeviceParametersValues;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParametersDataExport implements FromCollection,WithHeadings
{
    protected $from;
    protected $to;
    protected $devType;

    function __construct($from,$to,$devType) {
        $this->from = $from;
        $this->to = $to;
        $this->devType = $devType;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
//        dd();
        $device = Device::findOrFail($this->devType);
        $devPara =DeviceParametersValues::select('id','device_id','parameters','time_of_read')->where('device_id',$this->devType)->whereBetween('time_of_read', [date('Y-m-d',strtotime($this->from))." 00:00:00", date('Y-m-d',strtotime($this->to))." 23:59:59"])->get();
//        dd(count($devPara));
        foreach ($devPara as $para){
            $para->device_id = $device->device_id;
     }
        return $devPara;
    }

    public function headings(): array
    {
        return [
            'id',
            'device',
            'parameters',
            'time of read'


        ];
    }
}
