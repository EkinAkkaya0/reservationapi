<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens; // Eğer Passport kullanıyorsanız
use Tymon\JWTAuth\Contracts\JWTSubject; // JWTSubject için doğru import

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * JWTSubject interface methods
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // User ID döndürür
    }

    public function getJWTCustomClaims()
    {
        return []; // Özel bir payload yoksa boş döndürün
    }
}
