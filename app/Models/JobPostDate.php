<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPostDate extends Model
{

    protected $guarded = [];

    protected $casts = [
        'id'                  => 'integer',
        'job_post_id'         => 'integer',
        'date'                => 'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
