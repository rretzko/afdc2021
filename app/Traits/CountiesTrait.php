<?php

namespace App\Traits;

use App\Models\Userconfig;

trait CountiesTrait
{
    private $eventversionswithcounties = [65];

    private $counties = [
        37 => [1,2,3,4,5,6,7,8,9,10, //NJ includes "unknown" county
                11,12,13,14,15,16,17,18,19,20,21,22]
    ];

    private $usercounties = [
        45 => [1,6,7,9,15,17,19,], //bretzko
        56 => [4,11,12,16,20,], //cbreitzman
        249 => [5,8,10,21,13,], //kmarkowski
        423 => [2,3,14,18,],    //vlal
        108 => [4,11,12,16,20,],    //mdoheny
    ];

    public function geostateCounties($geostate_id = 37)
    {
        return $this->counties[$geostate_id];
    }

    public function userCounties($user_id, $eventversion_id = 65)
    {
        if(in_array($eventversion_id, $this->eventversionswithcounties)) {

            return array_key_exists($user_id, $this->usercounties)
                ? $this->usercounties[$user_id]
                : [];
        }

        return [];
    }

}
