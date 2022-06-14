<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceTypeParameter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order','length','rate'
    ];

    protected $guarded=[];
    protected $table = 'device_parameters_device_type';

    public function parameters()
    {
        return $this->hasMany(DeviceParameters::class);
    }

    public function types()
    {
        return $this->hasMany(DeviceType::class);
    }
}
