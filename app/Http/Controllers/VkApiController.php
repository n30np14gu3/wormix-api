<?php

namespace App\Http\Controllers;

use App\Http\Requests\VkApiRequest;
use App\Http\Resources\Vk\VkProfile;
use App\Models\User;
use App\Models\UserSocialData;

class VkApiController extends Controller
{
    public function handleRequest(VkApiRequest $request){
        return match ($request->post('method')) {
            'getUserSettings' => $this->getUserSettings(),
            'getAppFriends' => $this->getAppFriends(),
            'getProfiles' => $this->getProfiles(),
            default => "", //Other methods not needed
        };
    }

    private function getUserSettings()
    {
        return [
            'response' => 2371351
        ];
    }

    private function getAppFriends()
    {
        return [
            'response' => User::query()->select('id')->get()->pluck('id')->toArray(),
        ];
    }

    private function getProfiles()
    {
        return [
            'response' => VkProfile::collection(UserSocialData::all())
        ];
    }
}
