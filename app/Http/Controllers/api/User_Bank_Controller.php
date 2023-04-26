<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User_Bank_Resource;
use App\Models\User_Bank;
use Illuminate\Http\Request;

class User_Bank_Controller extends Controller
{
    //get all data user_bank
    public function index()
    {
        $bank = User_Bank::all();
        return new User_Bank_Resource(true, 'List Data User Bank', $bank);
    }
}
