<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\Uuids;

class User_Active extends Model
{
    use Uuids;
    use HasFactory, Notifiable;

    protected $table = 'user_actives';
    protected $fillable = [
        'phone_number',
        'email',
        'id_card',
        'tax_registration_number'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

}
