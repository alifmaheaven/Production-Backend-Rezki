<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Uuids;


class Campaign extends Model
{
    use Uuids;
    use HasFactory;
    protected $table = 'campaigns';

    protected $fillable = [
        'name',
        'description',
        'type',
        'target_funding_amount',
        'current_funding_amount',
        'start_date',
        'closing_date',
        'return_investment_period',
        'status',
        'document_name',
        'document_url',
        'category',
        'id_campaign_period',
        'id_user',
        'is_approved',
        'max_sukuk',
        'id_campaign_banner',
        'is_deleted',
    ];

    public function campaign_period()
    {
        return $this->belongsTo(CampaignPeriod::class, 'id_campaign_period', 'id');
    }

    public function campaign_banner()
    {
        return $this->belongsTo(CampaignBanner::class, 'id_campaign_banner', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
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
