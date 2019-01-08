<?php
namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class CurrService
{

    const PBAPICURSE = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
    const PBARHIVE = 'https://api.privatbank.ua/p24api/exchange_rates?json&date=';
    private $jdata;
    protected $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    private function connect($url)
    {

        $this->jdata = json_decode($this->client->get($url)->getBody());
        return $this->jdata;
    }

    public function getCurr()
    {

        try {

            $response = $this->connect(self::PBAPICURSE);

            $result = 'Course Privat Bank ' . PHP_EOL;
            foreach ($response as $arr) {

                $result .= '*******************' . PHP_EOL;
                $result .= 'Curse ' . $arr->ccy . PHP_EOL;
                $result .= 'Buy = ' . round($arr->buy, 2) . PHP_EOL;
                $result .= 'Sale = ' . round($arr->sale, 2) . PHP_EOL;
            }

            return $result;
        } catch (Exception $e) {

            return 'An unexpected error. Please try again later.';
        }

    }

    public function getArhiveCurr($date){
 
        try {
            return $date;
        } catch (Exception $e) {

            return 'An unexpected error. Please try again later.';
        }
    }

}
