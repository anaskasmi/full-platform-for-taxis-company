<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordNotification_dispatcher;
use Laravel\Passport\HasApiTokens;

class Dispatcher extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $gard = 'dispatcher';
    protected $table = 'dispatchers';
    protected $guarded=[];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification_dispatcher($token));
    }
}
