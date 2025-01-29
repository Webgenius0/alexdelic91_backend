<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'service_provider_id',
        'start_time',
        'end_time',
        'booking_date',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id'                  => 'integer',
        'user_id'             => 'integer',
        'service_provider_id' => 'integer',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'booking_date' => 'date:Y-m-d',
    ];

    public function overlaps($start_time, $end_time, $booking_date)
    {
        return self::where('booking_date', $booking_date)
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                      ->orWhereBetween('end_time', [$start_time, $end_time])
                      ->orWhereRaw('? BETWEEN start_time AND end_time', [$start_time])
                      ->orWhereRaw('? BETWEEN start_time AND end_time', [$end_time]);
            })
            ->exists();
    }

    /**
     * Get the user who made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the service provider for the booking.
     */
    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function bookingDetails()
    {
        return $this->belongsTo(ServiceProviderProfile::class,'service_provider_id');
    }
    
}
