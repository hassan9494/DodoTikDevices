<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilesParametersValues extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parameters', 'file_id', 'time_of_read'
    ];

    protected $guarded = [];
    protected $table = 'files_parameters_values';


    public function file()
    {
        return $this->belongsTo(FtpFile::class, 'file_id');
    }

    /**
     * Set the user's first name.
     *
     * @param string $value
     * @return void
     */
    public function setParametersAttribute($value)
    {
        unset($value[current(array_keys($value))]);
        $this->attributes['parameters'] = json_encode($value);
    }
}
