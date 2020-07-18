<?php



namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordNotification_driver;
use Laravel\Passport\HasApiTokens;

class Driver extends Authenticatable 
{
    use HasApiTokens, Notifiable;

    protected $table = 'drivers';
    protected $gard = 'driver';
    protected $guarded=[];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public $timestamps = true;
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification_driver($token));
    }
}
