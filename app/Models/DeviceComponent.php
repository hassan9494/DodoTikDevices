<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceComponent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'components', 'device_id' ,'settings'
    ];

    protected $guarded=[];
    protected $table = 'device_components';


    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }
}
