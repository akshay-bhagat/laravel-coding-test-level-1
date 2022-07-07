<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The name of the "created at" column.
     *
     */
    const CREATED_AT = 'createdAt';

    /**
     * The name of the "updated at" column.
     *
     */
    const UPDATED_AT = 'updatedAt';

    protected $casts = [
        'id' => 'string'
    ];

    protected $fillable = ['id','name','slug','startAt','endAt'];
    
    // Column for soft deletes
    protected $dates = ['deleted_at'];
}
