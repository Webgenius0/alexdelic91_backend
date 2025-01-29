<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookMark extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id'                  => 'integer',
        'user_id'             => 'integer',
        'booking_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function 
}
