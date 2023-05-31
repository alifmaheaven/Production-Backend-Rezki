<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Uuids;

class CampaignBanner extends Model
{
    use HasFactory;
    use Uuids;

    protected $table = 'campaign_banners';
    protected $fillable = [
        'id_banner',
        'id_campaign',
        'is_deleted',
    ];

    public function banner()
    {
        return $this->belongsTo(Banners::class, 'id_banner');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaigns::class, 'id_campaign');
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
