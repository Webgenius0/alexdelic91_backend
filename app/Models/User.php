<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Namu\WireChat\Traits\Chatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, Chatable;

    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Return the primary key of the user (id)
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'agree_to_terms',
        'password',
        'remember_token',
        'email_verified_at',
        'provider',
        'provider_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'email_verified_at' => 'datetime',
            'agree_to_terms'    => 'boolean',
            'is_premium'        => 'boolean',
            'id'                => 'integer',
            'is_notices'        => 'boolean',
            'is_messages'       => 'boolean',
            'is_likes'          => 'boolean',
            'safety_mode'       => 'boolean',
        ];
    }

    public function isAvailableForBooking($start_time, $end_time)
    {
        // Ensure the provider has a serviceProviderProfile
        if (!$this->serviceProviderProfile) {
            return false;
        }

        $available_from = $this->serviceProviderProfile->start_time;
        $available_to = $this->serviceProviderProfile->end_time;

        return $start_time >= $available_from && $end_time <= $available_to;
    }

    public function serviceProviderProfile()
    {
        return $this->hasOne(ServiceProviderProfile::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Get the user's profile.
     * @return HasMany
     */
    public function requestedHelp(): HasMany
    {
        return $this->hasMany(HelpCenter::class);
    }

    /**
     * Get the bookings made by the user (as a customer).
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Get the bookings where the user is the service provider.
     */
    public function providedServices()
    {
        return $this->hasMany(Booking::class, 'service_provider_id');
    }

    /**
     * Relationship with BookMarks (A user can bookmark many service providers)
     */
    public function bookmarks()
    {
        return $this->hasMany(BookMark::class);
    }

    /**
     * Relationship with BookMarks (A user can be bookmarked by many users)
     */
    public function bookmarkedBy()
    {
        return $this->hasMany(BookMark::class, 'service_provider_id');
    }
}
