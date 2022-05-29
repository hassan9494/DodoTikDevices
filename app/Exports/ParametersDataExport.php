<?php

namespace App\Exports;

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
        return DeviceParametersValues::all();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
    }
}
