<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User_Active_Resource;
use App\Models\User_Active;
use Illuminate\Http\Request;

class User_Active_Controller extends Controller
{
     //get all data user_active
     public function index()
     {
         $active = User_Active::all();
         return new User_Active_Resource(true, 'List Data User Active', $active);
     }

     
}
