<?php

namespace App\Models;

 use App\Jobs\HandleSendOTPJob;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Relations\HasMany;
 use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\CanPay;
use Bavix\Wallet\Interfaces\Customer;

class User extends Authenticatable implements Wallet, MustVerifyEmail, Customer
{
    use HasFactory, Notifiable, HasApiTokens, HasWallet, CanPay;

    /**
     * The attributes that are mass ascsignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_code',
        'number_phone',
        'otp_code',
        'otp_expired_at',
        'email_verified_at',
        'security_code',
        'profile_photo_path',
        'otp_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
        'otp_expired_at',
        'security_code'
    ];

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
            'otp_verified_at' => 'datetime',
        ];
    }

    protected $with = ['wallet'];

    /**
     * Mark the user's OTP as verified.
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'otp_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Determine if the user's OTP has been verified.
     *
     * @return bool
     */
    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->otp_verified_at);
    }

    /**
     * Send the OTP verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        HandleSendOTPJob::dispatch(
            $this->country_code,
            $this->number_phone,
            $this
        );
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
