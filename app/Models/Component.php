<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'desc','image'
    ];

    protected $guarded=[];
    protected $table = 'components';

    public function deviceComponents()
    {
        return $this->hasMany(DevicesComponents::class,'component_id');
    }

    public function componentSettings()
    {
        return $this->belongsToMany(ComponentSettings::class);
    }
}
