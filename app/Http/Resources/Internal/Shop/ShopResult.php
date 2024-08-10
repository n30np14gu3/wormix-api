<?php

namespace App\Http\Resources\Internal\Shop;

use App\Http\Resources\Internal\Account\WeaponRecordList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResult extends JsonResource
{
    public const Success = 0;
    public const Error = 1;
    public const MinRequirementsError = 2;
    public const NotEnoughMoney = 3;
    public const ConfirmFailure = 4;
    
    private int $result;

    private mixed $new_weapons;

    public function __construct($resource, int $result, $new_weapons) {
        $this->result = $result;
        $this->new_weapons = $new_weapons;
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Result' => $this->result,
            'Weapons' =>  WeaponRecordList::collection($this->new_weapons),
            'Stuff' => []
        ];
    }
}
