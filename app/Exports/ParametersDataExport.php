<?php

namespace App\Exports;

use App\Models\Device;
use App\Models\DeviceParametersValues;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParametersDataExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;
    protected $devType;

    function __construct($from, $to, $devType)
    {
        $this->from = $from;
        $this->to = $to;
        $this->devType = $devType;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $device = Device::findOrFail($this->devType);
        $devPara = DeviceParametersValues::select( 'parameters', 'time_of_read')
            ->where('device_id', $this->devType)
            ->whereBetween('time_of_read', [date('Y-m-d', strtotime($this->from)) . " 00:00:00", date('Y-m-d', strtotime($this->to)) . " 23:59:59"])
            ->get();

        foreach ($devPara as $para) {
            foreach ($device->deviceType->deviceParameters as $key => $type) {
                $x = $type->code;
                $para[$x] = json_decode($para->parameters, true)[$type->code];
            }
            $para->parameters = $device->device_id;
        }
        return $devPara;
    }


    public function headings(): array
    {
        $device = Device::findOrFail($this->devType);
        $headers = [
            'device',
            'time of read',

        ];
        foreach ($device->deviceType->deviceParameters as $type) {
            array_push($headers, $type->name . " (" . $type->unit . ")");
        }
        return $headers;
    }
}
