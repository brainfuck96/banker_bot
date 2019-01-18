<?php

use Illuminate\Database\Seeder;
use App\PrivatBankArhive;

class PBArhiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $years = $this->rangeData(2014, 2018);
        $months =$this->rangeData(1, 12);
        $days = $this->rangeData(1, 31);

        //DB::table('privat_bank_arhives')->delete();


        foreach ($years as $year){
            foreach ($months as $month){
                foreach ($days as $day){
                    $url = 'https://api.privatbank.ua/p24api/exchange_rates?json&date=';
                    $url .="$day.$month.$year";

                    if (null !== ($url)) {
                        $arrAll = json_decode(file_get_contents($url));

                        if (!empty($arrAll->exchangeRate)) {
                            $curr = $arrAll->exchangeRate;

                            foreach ($curr as $org) {

                                if(isset($org->currency)){

                                if ($org->currency == "RUB" && isset($org->saleRate)) {
                                    $coll['rub'] = [
                                        'sale' => $org->saleRate,
                                        'purchase' => $org->purchaseRate,
                                    ];
                                }
                                // else {
                                //     $coll['rub'] = [
                                //         'data' => $arrAll->date,
                                //         'sale' => null,
                                //         'purchase' => null,
                                //     ];

                                // }
                                if ($org->currency == "USD" && isset($org->saleRate)) {
                                    $coll['usd'] = [
                                        'sale' => $org->saleRate,
                                        'purchase' => $org->purchaseRate,
                                    ];
                                }
                                // else {

                                //     $coll['usd'] = [
                                //         'data' => $arrAll->date,
                                //         'sale' => null,
                                //         'purchase' => null,
                                //     ];
                                // }

                                if ($org->currency == "EUR" && isset($org->saleRate)) {
                                    $coll['eur'] = [
                                        'sale' => $org->saleRate,
                                        'purchase' => $org->purchaseRate,
                                    ];
                                }
                                // else {
                                //     $coll['eur'] = [
                                //         'data' => $arrAll->date,
                                //         'sale' => null,
                                //         'purchase' => null,
                                //     ];
                                // }

}
                            }

                            PrivatBankArhive::create(array(
                                'data' => $arrAll->date,
                                'day' =>$day,
                                'month'=>$month,
                                'year'=>$year,
                                'baseCurrencyLit'=>$arrAll->baseCurrencyLit,
                                'usd_sale' => $coll['usd']['sale'],
                                'usd_purchase' => $coll['usd']['purchase'],
                                'eur_sale' => $coll['eur']['sale'],
                                'eur_purchase' => $coll['eur']['purchase'],
                                'rub_sale' => $coll['rub']['sale'],
                                'rub_purchase' =>$coll['rub']['purchase'],

                            ));



                        }
                    }
                }
            }
        }
    }
    public function rangeData($begin, $end){

        $arr = [];
        foreach (range($begin, $end) as $num){
            $arr[] = $num;
        }

        return $arr;
    }
}
