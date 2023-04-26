<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Uuids;
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'date_of_birth',
        'full_name',
        'gender',
        'address',
        'id_card',
        'tax_registration_number',
        'email',
        'password',
        'employment_status',
        'id_user_active',
        'id_user_bank',
        'authorization_level'
    ];

    public function user_active()
    {
        return $this->belongsTo(User_Active::class, 'id_user_active');
    }

    public function user_bank()
    {
        return $this->belongsTo(User_Bank::class, 'id_user_bank');
    }
}
