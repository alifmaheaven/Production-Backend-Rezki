<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User_Resource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //get data user
    public function index()
     {
         $user= User::all();
         return new User_Resource(true, 'List Data User Active', $user);
     }

     //add data user
}
