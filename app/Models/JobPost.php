<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id'                  => 'integer',
        'user_id'             => 'integer',
        'category_id' => 'integer',
        'subcategory_id' => 'integer',
        'available_time'      => 'datetime:H:i',
    ];

    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobPostDates()
    {
        return $this->hasMany(JobPostDate::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
