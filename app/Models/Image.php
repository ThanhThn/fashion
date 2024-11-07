<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
        'path',
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';  // Specify UUID as string type
    protected $hidden = ['updated_at', 'created_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();  // Ensure UUID is set as string if id is empty
            }
        });
    }
}
