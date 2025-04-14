<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash; // <-- Import the Hash facade

class User extends Authenticatable // Optional: implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        // REMOVED: 'password' => 'hashed', // Remove this line
    ];

    /**
     * Set the user's password.
     * Automatically hash the password when setting the attribute.
     * This is an attribute mutator.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value): void // <-- ADD THIS METHOD
    {
        // Only hash the password if it's not already hashed
        // (prevents re-hashing if setting the attribute with an existing hash)
        // However, for create/update via validated data, it will always be plain text here.
        $this->attributes['password'] = Hash::make($value);
    }

    // --- Define Relationships (Example) ---
    /**
     * Get the saved recipes for the user.
     */
    // public function savedRecipes() // : HasMany
    // {
    //     return $this->hasMany(SavedRecipe::class);
    // }

    /**
     * Get the OTPs associated with the user.
     */
    // public function otps() // : HasMany
    // {
    //     return $this->hasMany(UserOtp::class);
    // }
}
