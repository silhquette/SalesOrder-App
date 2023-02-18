<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $primaryKey = 'order_code';
    protected $with = ['orders','customer'];
    public $incrementing = false;

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'purchase_order_id', 'id');
    }
}
