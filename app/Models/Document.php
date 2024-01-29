<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasFactory, Uuid;
    
    /**
     * Guard variable from mass assignment
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];
        
    /**
     * Overide new name of primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';
        
    /**
     * Including relationships
     *
     * @var array
     */
    protected $with = [
        'orders'
    ];
        
    /**
     * set incrementing on primary key
     *
     * @var bool
     */
    public $incrementing = false;
        
    /**
     * Relationship with orders table
     *
     * @return BelongsToMany
     */
    public function orders() : BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('additional');
    }
    
    /**
     * Keyword for route model binding
     *
     * @return string
     */
    public function getRouteKeyName() : string
    {
        return 'document_code';
    }
}
