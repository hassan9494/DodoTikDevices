<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceParameters extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code','unit'
    ];

    protected $guarded=[];
    protected $table = 'device_parameters';

    use SoftDeletes;

    public function devices()
    {
        return $this->belongsToMany(DeviceType::class);
    }

    public function parameterRangeColors()
    {
        return $this->hasMany(ParameterRangeColor::class,'parameter_id');
    }
}
