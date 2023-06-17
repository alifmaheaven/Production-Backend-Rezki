<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Uuids;

class CampaignReport extends Model
{
    use HasFactory;
    use Uuids;

    protected $table = 'campaign_reports';
    protected $fillable = [
        'id_campaign',
        'id_payment',
        'document_name',
        'document_url',
        'is_exported',
        'is_deleted',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'id_campaign');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id_payment');
    }

    public function campaign_report_details()
    {
        return $this->hasMany(CampaignReportGroup::class, 'id_campaign_report')
        ->select('campaign_report_details.*', 'campaign_report_groups.id_campaign_report as id_campaign_report','campaign_report_groups.id as id_campaign_report_group')
        ->join('campaign_report_details', 'campaign_report_details.id', '=', 'campaign_report_groups.id_campaign_report_detail');


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
