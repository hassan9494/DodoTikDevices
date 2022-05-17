<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $id)
 */
class DeviceParametersValues extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parameters', 'device_id' ,'time_of_read'
    ];

    protected $guarded=[];
    protected $table = 'device_parameters_values';


    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }
}
