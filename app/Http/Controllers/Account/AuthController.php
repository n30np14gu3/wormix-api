<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\SignInRequest;
use App\Http\Requests\Account\SignUpRequest;
use App\Models\User;
use App\Models\UserSocialData;
use App\Models\Wormix\UserProfile;
use App\Models\Wormix\UserWeapon;
use App\Models\Wormix\Weapon;
use App\Models\Wormix\WormData;
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

        $user->social_id = $user->id;
        $user->save();

        //Create user profile
        $user_profile = new UserProfile();
        $user_profile->user_id = $user->id;
        $user_profile->save();

        //Create user worm data
        $worm_data = new WormData();
        $worm_data->owner_id = $user->id;
        $worm_data->save();

        //Create user social data
        $social_data = new UserSocialData();
        $social_data->user_id = $user->id;
        $social_data->first_name = $user->login;
        $social_data->save();

        //Add starter user weapons
        $starter_weapons = Weapon::query()->where('is_starter', 1)->get();
        foreach($starter_weapons as $w){
            $weapon = new UserWeapon();
            $weapon->owner_id = $user->id;
            $weapon->weapon_id = $w->id;
            $weapon->save();
        }

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
