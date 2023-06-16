<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory, Uuid;

    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $with = ['product'];
    public $incrementing = false;

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function salesOrder() {
        return $this->belongsTo(SalesOrder::class);
    }

    public function documents() : BelongsToMany
    {
        return $this->BelongsToMany(Document::class);
    }
}
