<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $id)
 */
class LimitValues extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'min_value', 'max_value', 'device_id' ,'min_warning','max_warning'
    ];

    protected $guarded=[];
    protected $table = 'limit_values';

    protected $casts = [
        'min_value' => 'array',
        'max_value' => 'array',
        'min_warning' => 'bool',
        'max_warning' => 'bool',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }

}
