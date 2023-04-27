<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    // Add 'avatar' to the model
    protected function avatar():Attribute {
        return Attribute::make(get: function ($value) {
            return $value ? '/storage/avatars/'.$value : '/fallback-avatar.jpg';
        });
    }

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

    public function feedPosts () {
        return $this->hasManyThrough(
            Post::class, // Model that we end up with
            Follow::class, // Table has the data before accessing Post (1)
            'user_id', // culomn on Follow table with the user that follows
            'user_id', // foreigKey on the final model Post(1)
            'id', // local key, it's the User model, this file
            'followeduser'); // local key on the intermidiate table (Follow (2))
    }

    public function followers() { // who is following you
        return $this->hasMany(Follow::class, 'followeduser'); // foreignKey = followedUser
    }
    public function followingTheseUsers() { // those you are following
        return $this->hasMany(Follow::class, 'user_id'); // foreignKey = user_id
    }

    public function posts() {
        return $this->hasMany(Post::class, 'user_id');
    }
}
