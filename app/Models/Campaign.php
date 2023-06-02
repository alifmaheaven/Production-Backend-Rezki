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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function campaign_banners()
    {
        return $this->hasMany(CampaignBanner::class, 'id_campaign')->select('campaign_banners.*', 'banners.url', 'banners.name')
            ->join('banners', 'campaign_banners.id_banner', '=', 'banners.id')
            ->where('campaign_banners.is_deleted', false)
            ->where('banners.is_deleted', false);
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
