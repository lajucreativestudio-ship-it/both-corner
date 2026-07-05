<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'role_id', 'current_plan_id', 'subscription_status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function photoboothEvents()
    {
        return $this->hasMany(PhotoboothEvent::class);
    }

    public function devicePairingCodes()
    {
        return $this->hasMany(DevicePairingCode::class);
    }

    public function eventPhotos()
    {
        return $this->hasMany(EventPhoto::class);
    }

    public function currentPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'current_plan_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();
    }

    public function plan()
    {
        return $this->currentPlan;
    }
}
