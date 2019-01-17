<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivatBankArhivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privat_bank_arhives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data');
            $table->string('baseCurrencyLit');
            $table->string('usd_sale');
            $table->string('usd_purchase');
            $table->string('eur_sale');
            $table->string('eur_purchase');
            $table->string('rub_sale');
            $table->string('rub_purchase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privat_bank_arhives');
    }
}
