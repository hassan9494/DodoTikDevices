<?php

namespace App\Exports;

use App\Models\Device;
use App\Models\DeviceFactoryValue;
use App\Models\Factory;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FactoryDeviceValueExport implements FromCollection, WithHeadings
{
    protected $device;
    protected $factory;
    protected $from;
    protected $to;

    function __construct($from, $to,$device,$factory)
    {
        $this->from = $from;
        $this->to = $to;
        $this->device = $device;
        $this->factory = $factory;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $device = Device::findOrFail($this->device);
        $factory = Factory::findOrFail($this->factory);
        $devPara = DeviceFactoryValue::select('parameters','factory_id', 'time_of_read')
            ->where('device_id', $this->device)
            ->where('factory_id', $this->factory)
            ->whereBetween('time_of_read', [date('Y-m-d H:i:s', strtotime($this->from)) , date('Y-m-d H:i:s', strtotime($this->to))])
            ->get();
        foreach ($devPara as $para) {
            foreach ($device->deviceType->deviceParameters()->orderBy('order')->get() as $key => $type) {
                $x = $type->code;
                if (isset(json_decode($para->parameters, true)[$type->code])){
                    $para[$x] = json_decode($para->parameters, true)[$type->code];
                }else if (isset(json_decode($para->parameters, true)[$type->name])){
                    $para[$x] = json_decode($para->parameters, true)[$type->name];
                }else{
                    $para[$x] = "-";
                }
            }
            $para->parameters = $device->device_id;
            $para->factory_id = $factory->name;
            $para->time_of_read = Carbon::parse($para->time_of_read)->setTimezone('Europe/Istanbul')->format('Y-m-d H:i');
        }
        return $devPara;
    }


    public function headings(): array
    {
        $device = Device::findOrFail($this->device);
        $headers = [
            'device',
            'factory',
            'time of read',
        ];
        foreach ($device->deviceType->deviceParameters()->orderBy('order')->get() as $type) {
            array_push($headers, $type->name . " (" . $type->unit . ")");
        }
        return $headers;
    }
}
