<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceTypeSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
    ];

    protected $guarded=[];
    protected $table = 'device_settings_device_type';

    public function Settings()
    {
        return $this->hasMany(DeviceSettings::class);
    }

    public function types()
    {
        return $this->hasMany(DeviceType::class);
    }
}
