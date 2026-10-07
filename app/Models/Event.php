<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

  protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'place',
        'capacity',
        'is_free',
        'price',
        'image',
        'status',
        'category_id',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_free'    => 'boolean',
        'price'      => 'decimal:2',
        'capacity'   => 'integer',
    ];
    public function category()
{
    return $this->belongsTo(Category::class);
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function registrations()
{
    return $this->hasMany(Registration::class);
}

public function users()
{
    return $this->belongsToMany(User::class, 'registrations');
}

}

