<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceSettingPerDevice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'settings', 'device_id'
    ];

    protected $guarded=[];
    protected $table = 'device_setting_per_devices';


    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }

}
