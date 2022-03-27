<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'device_id','user_id'
    ];

    protected $guarded=[];
    protected $table = 'devices';

    use SoftDeletes;

    public function deviceSettings()
    {
        return $this->belongsToMany(DeviceSettings::class);
    }

    public function deviceParameters()
    {
        return $this->belongsToMany(DeviceParameters::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
