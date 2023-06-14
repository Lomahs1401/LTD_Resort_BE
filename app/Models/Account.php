<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Account extends Authenticatable implements JWTSubject
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 
        'email',
        'password', 
        'avatar', 
        'enabled',
        'role_id',
        'reset_code',
        'reset_code_expires_at',
        'reset_code_attempts'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'reset_code',
        'reset_code_expires_at',
        'reset_code_attempts'
    ];

    // Rest omitted for brevity

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
     *s
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function customer()
    {
    return $this->hasOne(Customer::class, 'account_id');
    }
    public function employee()
    {
    return $this->hasOne(Employee::class);
    }
    public function admin()
    {
    return $this->hasOne(Admin::class);
    }
}
