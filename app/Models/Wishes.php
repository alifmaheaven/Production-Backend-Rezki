<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Wishes extends Model
{
        use Uuids;
        use HasFactory, Notifiable;
    
        protected $fillable = [
            'wishes'
        ];
    
    }

