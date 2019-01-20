<?php
namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class CurrService
{

    const PBAPICURSE = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
    const PBARHIVE = 'https://api.privatbank.ua/p24api/exchange_rates?json&date=';
    const ALLBANKSARHIVE = 'http://resources.finance.ua/ru/public/currency-cash.json';
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

    private function getAllCollectionCurr()
    {
        return $this->connect(self::PBAPICURSE);
    }

    public function getColCurr()
    {
        $response = $this->connect(self::PBAPICURSE);
        $arrCur = [];
        foreach ($response as $cur) {
            $arrCur[] = $cur->ccy;
        }

        return $arrCur;
    }

    public function getConversValue($value, $cur, $ask)
    {
        $arrAll = $this->getAllCollectionCurr();

        foreach ($arrAll as $arr) {

            if ($arr->ccy == $cur) {
                $num = round($arr->$ask, 2);
                //if($ask == 'b')
                $prod = round($value * $num, 2);
                $baseCur = $arr->base_ccy;
            }
        }
        $result = "$value $cur= $prod $baseCur" . PHP_EOL;
        $result .= "official course PrivatBank" . PHP_EOL;
        $result .= "1 $cur = $num $baseCur " . PHP_EOL;
        return $result;

    }

    public function getCurr()
    {

        try {

            $response = $this->connect(self::PBAPICURSE);

            $result = 'Course Privat Bank ' . PHP_EOL;
            foreach ($response as $arr) {

                $result .= '*******************' . PHP_EOL;
                $result .= 'Curse ' . $arr->ccy . PHP_EOL;
                $result .= 'Sale = ' . round($arr->sale, 2) . ' ' . $arr->base_ccy . PHP_EOL;
                $result .= 'Buy = ' . round($arr->buy, 2) . ' ' . $arr->base_ccy . PHP_EOL;

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
            $response = json_decode(file_get_contents(self::PBARHIVE . $date)); //$this->connect(self::PBARHIVE.$date);
            $result = 'Course Privat Bank, DATA = ' . $response->date . PHP_EOL;
            $arrExchange = $response->exchangeRate;

            foreach ($arrExchange as $arr) {

                if (isset($arr->saleRate)) {
                    if ($arr->currency == "USD" || $arr->currency == "EUR" || $arr->currency == "RUB") {

                        $result .= '*******************' . PHP_EOL;
                        $result .= 'Curse ' . $arr->currency . PHP_EOL;
                        $result .= 'Sale = ' . round($arr->saleRate, 2) . ' ' . $arr->baseCurrency . PHP_EOL;
                        $result .= 'Buy = ' . round($arr->purchaseRate, 2) . ' ' . $arr->baseCurrency . PHP_EOL;
                    }
                }
                // else{
                //     $result = 'Incorrect DATA... Please try again';
                // }
            }

            return $result;

        } catch (Exception $e) {

            return 'An unexpected error. Please try again later or enter correct data';
        }
    }

    // public function getCurrAll($curr){
    //     try {
    //         //if($date)
    //         $response =  json_decode(file_get_contents(self::ALLBANKSARHIVE));//$this->connect(self::PBARHIVE.$date);
    //         $result = $curr.PHP_EOL;
    //         // $arrExchange = $response->exchangeRate;

    //         // foreach ($arrExchange as $arr) {
    //         //     if ($arr->currency == "USD" || $arr->currency == "EUR" || $arr->currency == "RUB") {

    //         //         $result .= '*******************' . PHP_EOL;
    //         //         $result .= 'Curse ' . $arr->currency . PHP_EOL;
    //         //         $result .= 'Buy = ' . round($arr->purchaseRate, 2) . ' ' . $arr->baseCurrency . PHP_EOL;
    //         //         $result .= 'Sale = ' . round($arr->saleRate, 2) . ' ' . $arr->baseCurrency . PHP_EOL;
    //         //     }
    //         // }

    //         return $result;

    //     } catch (Exception $e) {

    //         return 'An unexpected error. Please try again later or enter correct data';
    //     }
    // }

}
