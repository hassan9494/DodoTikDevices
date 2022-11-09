<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceFactoryValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id','factory_id','parameters','time_of_read'
    ];

    protected $guarded=[];
    protected $table = 'device_factory_values';

    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class,'factory_id');
    }

    public function deviceFactoryValues(){
        return $this->belongsTo(DeviceFactory::class,'device_factory_id');
    }
}
