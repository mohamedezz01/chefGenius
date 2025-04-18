<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash; // Ensure Hash is imported if using mutator
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Import HasMany

class User extends Authenticatable // Optional: implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // REMOVED: 'password' => 'hashed', // Use mutator below instead if cast fails
    ];

    /**
     * Set the user's password (Mutator for hashing).
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the saved recipes for the user.
     * Defines a one-to-many relationship.
     */
    public function savedRecipes(): HasMany // <-- ADD THIS METHOD
    {
        return $this->hasMany(SavedRecipe::class);
    }

    /**
     * Get the OTPs associated with the user.
     * (Keep this if using the UserOtp model)
     */
    public function otps(): HasMany // <-- ADD THIS METHOD if using UserOtp model
    {
         return $this->hasMany(UserOtp::class);
    }
}
