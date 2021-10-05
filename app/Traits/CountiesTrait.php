<?php

namespace App\Traits;

trait CountiesTrait
{
    private $counties = [
        37 => [1,2,3,4,5,6,7,8,9,10, //NJ includes "unknown" county
                11,12,13,14,15,16,17,18,19,20,21,22]
    ];

    private $usercounties = [
        45 => [1,6,7,9,15,17,19,],
        56 => [4,11,12,16,20,],
        249 => [5,8,10,21,13,],
        423 => [2,3,14,18,]
    ];

    public function geostateCounties($geostate_id = 37)
    {
        return $this->counties[$geostate_id];
    }

    public function userCounties($user_id)
    {
        return $this->usercounties[$user_id];
    }

}
