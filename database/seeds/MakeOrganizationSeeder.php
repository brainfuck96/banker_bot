<?php

use App\Organization;
use Illuminate\Database\Seeder;

class MakeOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizations')->delete();

        $str = file_get_contents('http://resources.finance.ua/ru/public/currency-cash.json');
        $arrAll = json_decode($str);

        $data_request = $arrAll->date;

        foreach ($arrAll->organizations as $organization) {
            if ($organization->orgType == 1) {
                if (isset($organization->currencies->USD) &&
                    isset($organization->currencies->EUR) &&
                    isset($organization->currencies->RUB)) {

                    Organization::create(array(
                        'id_org' => $organization->id,
                        'orgType' => $organization->orgType,
                        'title' => $organization->title,
                        'phone' => $organization->phone,
                        'date_bid' => $data_request,
                        'usd_ask' => $organization->currencies->USD->ask,
                        'usd_bid' => $organization->currencies->USD->bid,
                        'eur_ask' => $organization->currencies->EUR->ask,
                        'eur_bid' => $organization->currencies->EUR->bid,
                        'rub_ask' => $organization->currencies->RUB->ask,
                        'rub_bid' => $organization->currencies->RUB->bid,
                    ));
                }

            }

        }

    }
}
