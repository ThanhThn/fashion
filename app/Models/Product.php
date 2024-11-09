<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'sort_description',
        'description',
        'category_id',
        'thumbnail_id',
        'variants',
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';  // UUID is a string, not an integer
    protected $hidden = ['updated_at', 'created_at'];

    protected $casts = [
        'variants' => 'array',  // Laravel sẽ tự động giải mã JSON thành mảng
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();  // Cast UUID to string if necessary
            }
        });
    }

    public function thumbnail()
    {
        return $this->belongsTo(Image::class, 'thumbnail_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

}
