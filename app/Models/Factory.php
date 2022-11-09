<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected $guarded=[];
    protected $table = 'factories';



    public function deviceFactories()
    {
        return $this->hasMany(DeviceFactory::class,'factory_id');
    }

    public function deviceFactoryValues()
    {
        return $this->hasMany(DeviceFactoryValue::class,'factory_id');
    }
}
