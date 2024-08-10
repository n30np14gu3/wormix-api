<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\Account\UserResource;
use App\Models\User;
use App\Modules\UserSession;
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

    public function updateAccount(UpdateAccountRequest $request)
    {

    }

    public function getAccountInfo(string $user_login)
    {

    }

    /**
     * @throws \Exception
     */
    public function startGame(Request $request)
    {
        $user_session = new UserSession($request->user()->id);
        return response([
            'auth_key' => $user_session->setAuthKey()
        ]);
    }
}
