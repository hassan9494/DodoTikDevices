<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentSettings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug' ,'settings'
    ];

    protected $guarded=[];
    protected $table = 'component_settings';

    public function components()
    {
        return $this->belongsToMany(Component::class);
    }


}
