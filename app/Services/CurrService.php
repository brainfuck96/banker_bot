<?php
namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class CurrService{

    const PBAPICURSE = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
    protected $client;
    //public $result = [];

    public function __construct()
    {
        $this->client = new Client;
    }
    
    public function getCurr(){

        try {
            
            $response = json_decode($this->client->get(self::PBAPICURSE)->getBody());

            $result = 'Course Privat Bank ' . PHP_EOL;
            foreach ($response as $arr) {
    

                $result .= '*******************' . PHP_EOL;
                $result .= 'Curse ' . $arr->ccy . PHP_EOL;
                $result .= 'Buy = ' . round($arr->buy, 2) . PHP_EOL;
                $result .= 'Sale = ' . round($arr->sale, 2) . PHP_EOL;
            }
    
            return $result;
        }

       
         catch (Exception $e) {

           return 'An unexpected error. Please try again later.';
         }
    
    }
    
}