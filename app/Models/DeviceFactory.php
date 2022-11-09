<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceFactory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id','factory_id','start_date','is_attached'
    ];

    protected $guarded=[];
    protected $table = 'device_factories';

    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class,'factory_id');
    }

    public function deviceFactoryValues(){
        return $this->hasMany(DeviceFactoryValue::class,'device_factory_id');
    }
}
