<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected $guarded=[];
    protected $table = 'device_types';

    use SoftDeletes;


    public function devices()
    {
        return $this->hasMany(Device::class,'type_id');
    }



    public function deviceSettings()
    {
        return $this->belongsToMany(DeviceSettings::class)->withPivot('value');
    }

    public function deviceParameters()
    {
        return $this->belongsToMany(DeviceParameters::class)->withPivot('order','length','rate');
    }
}
