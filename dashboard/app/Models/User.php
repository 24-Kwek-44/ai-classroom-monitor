<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Represents an application user.
 *
 * This User model has been customized from the standard Laravel setup.
 * Notably, it uses a 'teacher_id' as a unique identifier instead of an 'email'
 * and does not implement the MustVerifyEmail interface, meaning email verification
 * is not a feature of this user type.
 */
class User extends Authenticatable
{
    // Includes standard Laravel traits for model factories and notifications.
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * Mass assignment allows for creating or updating a model using an array of data.
     * Only the attributes listed here can be filled this way, which is a security
     * measure to prevent unintentionally exposing sensitive model properties.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        // 'teacher_id' is used instead of 'email' as the primary login identifier.
        'teacher_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * When this model is converted to an array or JSON, the attributes listed here
     * will be omitted. This is crucial for security, preventing sensitive data like
     * password hashes or session tokens from being exposed in API responses.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * This method defines how certain attributes are automatically converted to
     * common data types when you access them on the model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // The 'email_verified_at' cast is absent because this model doesn't
            // support email verification.

            // The 'hashed' cast is a modern Laravel feature that automatically
            // hashes the password whenever it's set on the model, ensuring
            // all stored passwords are secure.
            'password' => 'hashed',
        ];
    }
}