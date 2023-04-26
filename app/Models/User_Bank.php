<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Notifications\Notifiable;

class User_Bank extends Model
{
    use Uuids;
    use HasFactory, Notifiable;

    protected $table = 'user_banks';
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_bank'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
