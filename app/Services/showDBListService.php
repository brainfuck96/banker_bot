<?php

namespace App\Services;


use App\Organization;

class showDBListService
{
    public function showDB(){
     //  $baza = Organization::all();
       $result = "DB List ".PHP_EOL;
//       foreach ($baza as $bank){
//           $result .= $bank->title.PHP_EOL;
//       }
        //$s = 'THIS IS DB';
        return $result;
    }
}