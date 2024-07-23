<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Verified;
use Laravel\Passport\HasApiTokens;
use App\Models\Chat;
use App\Models\Company;
use App\Models\FreelancingOwner;
use App\Notifications\MessageSent;
use LamaLama\Wishlist\HasWishlists;

class User extends Authenticatable implements MustVerifyEmail
{
    use  HasFactory, Notifiable, HasApiTokens ,HasWishlists;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'role',
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

    public function hasVerifiedEmail() {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified()
    {
        $this -> email_verified_at = Carbon::now();
        $this -> save();
        event(new Verified($this));
        return true;
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function job_seeker() {
        return $this->hasOne(JobSeeker::class);
    }

    public function company() {
        return $this->hasOne(Company::class);
    }

    public function freelancing_owner() {
        return $this->hasOne(FreelancingOwner::class);
    }

    public function chats(){
        return $this->hasMany(Chat::class,'created_by');
    }

    public function routeNotificationForOneSignal() {
        return [
            'tags' => [
                'key' => 'userId' ,
                'relation' => '=' ,
                'value' => (string)($this->id)
            ]
        ];
    }

    public function sendNewMessageNotification(array $data) {
        $this->notify(new MessageSent($data));
    }
}
