<?php
namespace App\Services;

use Exception;

class CurrAllBanksService
{
    public function getAllOrganization()
    {
        $str = file_get_contents('http://resources.finance.ua/ru/public/currency-cash.json');
        $arrAll = json_decode($str);
        return $arrAll;
    }
    public function getData()
    {
        return $this->getAllOrganization()->date;
    }

    public function getAllBanksList($org = 'bank', $curr = true)
    {
        $arr = $this->getAllOrganization();
        $data = $arr->date;

        foreach ($arr->organizations as $value) {

            if ($value->orgType == 1) {

                $all_banks[] = $value;
            } else {
                $all_exchanges[] = $value;
            }

        }

        if ($org == 'bank') {
            return $all_banks;
        } else {
            return $all_exchanges;
        }

    }
    public function getSomeBank($some_org)
    {
        try {
            $curentArray = ['USD', 'EUR', 'RUB'];
            $allbanks = $this->getAllBanksList();
            // $data = $this->getAllBanksList()->date;
            $result = 'Course Data: ' . $this->getData() . PHP_EOL;
            foreach ($allbanks as $bank) {
                if ($bank->id == $some_org) {
                    $result .= '**************************' . PHP_EOL;
                    $result .= 'Organization : ' . $bank->title . PHP_EOL;
                    foreach ($curentArray as $currenci) {
                        if (isset($bank->currencies->$currenci)) {
                            $val = $bank->currencies->$currenci;
                            $result .= '----------------' . PHP_EOL;
                            $result .= 'Curse ' . $currenci . PHP_EOL;
                            $result .= 'Sale = ' . round($val->ask, 2) . ' UAH' . PHP_EOL;
                            $result .= 'Buy = ' . round($val->bid, 2) . ' UAH' . PHP_EOL;
                        }
                    }
                }
            }
            return $result;
        } catch (Exception $e) {

            return 'An unexpected error. Please try again later ';
        }

    }

    // public function getSomeBank($some_org = 1233, $some_cur = 'USD')
    // {
    //     try {
    //         $result = '';
    //         $allbanks = $this->getAllBanksList();
    //         foreach ($allbanks as $bank) {
    //             if ($bank->oldId == $some_org && isset($bank->currencies->$some_cur)) {
    //                 // if (isset($bank->currencies->$some_cur)) {
    //                 $result .= '**************************' . PHP_EOL;
    //                 $result .= 'Organization : ' . $bank->title . PHP_EOL;
    //                 $val = $bank->currencies->$some_cur;
    //                 $result .= '' . PHP_EOL;
    //                 $result .= 'Curse ' . $some_cur . PHP_EOL;
    //                 $result .= 'Sale = ' . round($val->ask, 2) . ' UAH' . PHP_EOL;
    //                 $result .= 'Buy = ' . round($val->bid, 2) . ' UAH' . PHP_EOL;
    //             }
    //             // }
    //         }
    //         return $result;
    //     } catch (Exception $e) {

    //         return 'An unexpected error. Please try again later ';
    //     }

    // }
}
