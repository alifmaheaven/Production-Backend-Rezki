<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Uuids;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    use Uuids;

    protected $fillable = [
        'amount',
        'id_user',
        'id_campaign',
        'sukuk',
        'administration_fee',
        'is_deleted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'id_campaign', 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user = Auth::user();
            $model->created_by = isset($user->email) ? $user->email : 'system';
            $model->updated_by = isset($user->email) ? $user->email : 'system';
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
        static::updating(function ($model) {
            $user = Auth::user();
            $model->updated_by = isset($user->email) ? $user->email : 'system';
            $model->version = $model->version + 1;
        });
    }
}
