<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory, Uuid;

    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $with = ['customer'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function getRouteKeyName(): string
    {
        return 'order_code';
    }
}
