<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\SignInRequest;
use App\Http\Requests\Account\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function respondWithToken(User $user){
        $auth_token = $user->createToken('auth_token-'.$user->id)->plainTextToken;
        return response()->json([
            'auth_token' => $auth_token,
            'id' => $user->id,
        ]);
    }
    public function signIn(SignInRequest $request)
    {
        $user = User::query()->where('login', $request->json('login'))->get()->first();
        if(!Hash::check($request->json('password'), $user->password))
            abort(403);

        return $this->respondWithToken($user);
    }

    public function signUp(SignUpRequest $request)
    {
        //Create user
        $user = new User();
        $user->password = $request->json('password');
        $user->login = $request->json('login');
        $user->save();

        return $this->respondWithToken($user);
    }

    public function signOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response([
           'message' => 'Logged out'
        ]);
    }
}
