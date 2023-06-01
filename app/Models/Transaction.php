<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Uuids;

class Transaction extends Model
{
    use HasFactory;
    use Uuids;

    protected $table = 'transactions';
    protected $fillable = [
        'id_campaign',
        'id_user',
        'id_receipt',
        'investor_amount',
        'sukuk',
        'service_fee',
        'status',
        'profit',
        'is_deleted',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaigns::class, 'id_campaign');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'id_user');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipts::class, 'id_receipt');
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
