<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory, Uuid;

    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $with = ['order'];
    public $incrementing = false;
    
    public function order()
    {
        return $this->BelongsTo(Order::class);
    }

    public function getRouteKeyName(): string
    {
        return 'document_code';
    }
}
