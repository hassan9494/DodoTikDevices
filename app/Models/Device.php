<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $string, $id)
 * @method static findOrFail(int $id)
 */
class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'device_id','user_id','type_id'
    ];

    protected $guarded=[];
    protected $table = 'devices';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class,'type_id');
    }

    public function deviceSetting()
    {
        return $this->hasOne(DeviceSettingPerDevice::class,'device_id');
    }

    public function limitValues()
    {
        return $this->hasOne(LimitValues::class,'device_id');
    }

    public function deviceParameters()
    {
        return $this->hasMany(DeviceParametersValues::class,'device_id');
    }

    public function deviceComponent()
    {
        return $this->hasOne(DeviceComponent::class,'device_id');
    }
}



