<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['product'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function purchaseOrder() {
        return $this->belongsTo(purchaseOrder::class);
        // return $this->belongsTo(purchaseOrder::class, 'order_number_id', 'id');
    }
}
