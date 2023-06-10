<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Customer extends Model
{
    use HasFactory, Uuid;

    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function salesOrders() {
        return $this->hasMany(SalesOrder::class);
    }
    
    public function getRouteKeyName(): string
    {
        return 'code';
    }
}
