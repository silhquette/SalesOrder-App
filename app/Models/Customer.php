<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $primaryKey = 'code';
    public $incrementing = false;

    public function purchaseOrders() {
        return $this->hasMany(PurchaseOrder::class);
    }
}
