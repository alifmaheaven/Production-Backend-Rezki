<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Traits\Uuids;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use Uuids;
    protected $table = 'users';

    protected $fillable = [
        'id_user_active',
        'id_user_bank',
        'id_user_business',
        'id_user_heir',
        'id_user_image',
        'full_name',
        'date_of_birth',
        'gender',
        'address',
        'id_card',
        'tax_registration_number',
        'email',
        'password',
        'employment_status',
        'authorization_level',
        'phone_number',
        'marital_status',
        'is_deleted',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
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

    public function user_active()
    {
        return $this->belongsTo(UserActive::class, 'id_user_active');
    }

    public function user_bank()
    {
        return $this->belongsTo(UserBank::class, 'id_user_bank');
    }

    public function user_business()
    {
        return $this->belongsTo(UserBusiness::class, 'id_user_business');
    }

    public function user_heir()
    {
        return $this->belongsTo(UserHeir::class, 'id_user_heir');
    }

    public function user_image()
    {
        return $this->belongsTo(UserImage::class, 'id_user_image');
    }

    public static function boot()
    {
       parent::boot();
       static::creating(function($model)
       {
           $user = Auth::user();
           $model->created_by = isset($user->email) ? $user->email : 'system';
           $model->updated_by = isset($user->email) ? $user->email : 'system';
           $model->id = (string) \Illuminate\Support\Str::uuid();
       });
       static::updating(function($model)
       {
           $user = Auth::user();
           $model->updated_by = isset($user->email) ? $user->email : 'system';
           $model->version = $model->version + 1;
       });
   }

}
