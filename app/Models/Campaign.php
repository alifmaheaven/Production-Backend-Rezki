<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Uuids;

class Campaign extends Model
{
    use HasFactory;
    use Uuids;

    protected $table = 'campaigns';
    protected $fillable = [
        'id_user',
        'name',
        'description',
        'type',
        'target_funding_amount',
        'current_funding_amount',
        'start_date',
        'closing_date',
        'return_investment_period',
        'status',
        'prospektus_url',
        'category',
        'is_approved',
        'max_sukuk',
        'tenors',
        'profit_share',
        'sold_sukuk',
        'is_deleted',
        'version',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'id_user');
    }

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
