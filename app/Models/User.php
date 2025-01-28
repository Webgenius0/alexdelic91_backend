<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

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
            'email_verified_at' => 'datetime',
            'agree_to_terms'    => 'boolean',
            'is_premium'        => 'boolean',
            'id'                => 'integer',
        ];
    }

    public function serviceProviderProfile()
    {
        return $this->hasOne(ServiceProviderProfile::class);
    }

<<<<<<< HEAD
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'user_id');
    }

    public function serviceProviderBookmarks()
    {
        return $this->hasMany(Bookmark::class, 'service_provider_id');
    }

    public function feedbacksGiven()
    {
        return $this->hasMany(Feedback::class, 'user_id');
    }

    public function feedbacksReceived()
    {
        return $this->hasMany(Feedback::class, 'service_provider_id');
    }

=======
    /**
     * Get the user's profile.
     * @return HasMany
     */
    public function requestedHelp(): HasMany
    {
        return $this->hasMany(HelpCenter::class);
    }
>>>>>>> 143079173d23f49998641d4b9d613f52c16711e5
}
