<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevicesComponents extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'component_id', 'device_id' ,'settings','order','width'
    ];

    protected $guarded=[];
    protected $table = 'devices_components';


    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }

    public function component()
    {
        return $this->belongsTo(Component::class,'component_id');
    }
}
