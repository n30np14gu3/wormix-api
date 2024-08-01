<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function getAccount(Request $request)
    {
        return new UserResource($request->user()->load([
            'user_profile',
            'worm_data',
            'social_data'
        ]));
    }

    public function updateAccount()
    {

    }

    public function getAccountInfo(string $user_login)
    {

    }

    public function startGame()
    {

    }
}
