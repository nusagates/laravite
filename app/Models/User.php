<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $appends = ['role_names', 'permission_names', 'avatar'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRoleNamesAttribute()
    {
        return $this->getRoleNames();
    }
    public function getPermissionNamesAttribute()
    {
        return $this->getPermissionNames();
    }
    public function getAvatarAttribute(){
        $avatar = public_path('upload/avatar/'.$this->id.'.png');
        return is_file($avatar)?url('upload/avatar/'.$this->id.'.png?'.now()->timestamp):url('assets/images/logo.png');
    }

}
