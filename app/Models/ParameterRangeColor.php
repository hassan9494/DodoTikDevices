<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterRangeColor extends Model
{
    use HasFactory; /**
 * The attributes that are mass assignable.
 *
 * @var array
 */
    protected $fillable = [
        'from', 'to','color','parameter_id','level_name','description'
    ];

    protected $guarded=[];
    protected $table = 'parameter_range_colors';

    public function deviceParameter()
    {
        return $this->belongsTo(DeviceParameters::class,'parameter_id');
    }
}
