<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'email',
        'payment_method',
        'order_total',
        'order_status'
    ];

    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
     return $this->belongsTo(User::class, "user_id", "id");
    }
    public function products(){
        return $this->hasMany(Order_Product::class, "order_id", "id");
    }
}
