<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(MakeOrganizationSeeder::class);// make DB All Banks
       $this->call(PBArhiveSeeder::class); // make Arhive
    }
}
