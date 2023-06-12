<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Uuids;

class campaign_report_group extends Model
{
    use HasFactory;
    use Uuids;

    protected $table = 'campaign_report_groups';
    protected $fillable = [
        'id_campaign_report',
        'id_campaign_report_detail',
        'is_deleted',
    ];

    public function campaign_report()
    {
        return $this->belongsTo(CampaignReport::class, 'id_campaign_report');
    }

    public function campaign_report_detail()
    {
        return $this->belongsTo(CampaignReportDetail::class, 'id_campaign_report_detail');
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
