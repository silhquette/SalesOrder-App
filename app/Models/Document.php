<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasFactory, Uuid;

    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $with = ['orders'];
    public $incrementing = false;
    
    public function orders() : BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function getRouteKeyName() : string
    {
        return 'document_code';
    }
}
