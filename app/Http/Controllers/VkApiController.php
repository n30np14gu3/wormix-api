<?php

namespace App\Http\Controllers;

use App\Http\Requests\VkApiRequest;
use App\Models\User;

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

    //TODO: implement via DB
    private function getAppFriends()
    {
        return [
            'response' => User::query()->select('id')->get()->pluck('id')->toArray(),
        ];
    }

    //TODO: implement via DB
    private function getProfiles()
    {
        return [
            'response' => [
                [
                    "uid"=> "1",
                    "first_name"=> "ShockByte",
                    "last_name"=> "",
                    "nickname"=> "",
                    "sex"=> 1,
                    "bdate"=> "23.11.2000",
                    "country"=> 1,
                    "city"=> 1,
                    "timezone"=> 1,
                    "photo"=> "",
                    "photo_medium"=> "",
                    "photo_big"=> "",
                    "has_mobile"=> 1,
                ],
                [
                    "uid"=> "2",
                    "first_name"=> "ShockByte",
                    "last_name"=> "",
                    "nickname"=> "",
                    "sex"=> 1,
                    "bdate"=> "23.11.2000",
                    "country"=> 1,
                    "city"=> 1,
                    "timezone"=> 1,
                    "photo"=> "",
                    "photo_medium"=> "",
                    "photo_big"=> "",
                    "has_mobile"=> 1,
                ],
                [
                    "uid"=> "3",
                    "first_name"=> "ShockByte",
                    "last_name"=> "",
                    "nickname"=> "",
                    "sex"=> 1,
                    "bdate"=> "23.11.2000",
                    "country"=> 1,
                    "city"=> 1,
                    "timezone"=> 1,
                    "photo"=> "",
                    "photo_medium"=> "",
                    "photo_big"=> "",
                    "has_mobile"=> 1,
                ]
            ]
        ];
    }
}
