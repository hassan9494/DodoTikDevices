<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 * @method static findOrFail(int $id)
 */
class FtpFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'extension'
    ];

    protected $guarded=[];
    protected $table = 'files';

    public function fileParameters()
    {
        return $this->hasMany(FilesParametersValues::class,'file_id');
    }
}
