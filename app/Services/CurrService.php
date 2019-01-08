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
                $result .= 'Buy = ' . round($arr->buy, 2) . ' ' . $arr->base_ccy . PHP_EOL;
                $result .= 'Sale = ' . round($arr->sale, 2) . ' ' . $arr->base_ccy . PHP_EOL;
            }

            return $result;
        } catch (Exception $e) {

            return 'An unexpected error. Please try again later.';
        }

    }

    public function getArhiveCurr($date)
    {

        try {
            //if($date)
            $response = $this->connect(self::PBARHIVE.$date);
            $result = 'Course Privat Bank, DATA = ' . $date.PHP_EOL;
            $arrExchange = $response->exchangeRate;

            foreach ($arrExchange as $arr) {
                if ($arr->currency == "USD" || $arr->currency == "EUR" || $arr->currency == "RUB") {

                    $result .= '*******************' . PHP_EOL;
                    $result .= 'Curse ' . $arr->currency . PHP_EOL;
                    $result .= 'Buy = ' . round($arr->purchaseRate, 2) . ' ' . $arr->baseCurrency . PHP_EOL;
                    $result .= 'Sale = ' . round($arr->saleRate, 2) . ' ' . $arr->baseCurrency . PHP_EOL;
                }
                else{
                    $result = 'Incorrect DATA... Please try again';
                }
            }

            return $result;

        } catch (Exception $e) {

            return 'An unexpected error. Please try again later or enter correct data';
        }
    }

}
