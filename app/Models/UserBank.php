<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Uuids;

class UserBank extends Model
{
    use HasFactory;
    use Uuids;
    protected $table = 'user_banks';
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'is_deleted',
    ];

    public static function boot()
     {
        parent::boot();
        static::creating(function($model)
        {
            $user = Auth::user();
            $model->created_by = isset($user->email) ? $user->email : 'system';
            $model->updated_by = isset($user->email) ? $user->email : 'system';
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
        static::updating(function($model)
        {
            $user = Auth::user();
            $model->updated_by = isset($user->email) ? $user->email : 'system';
            $model->version = $model->version + 1;
        });
    }
}
