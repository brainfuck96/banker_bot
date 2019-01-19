<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_org');
            $table->integer('orgType');
            $table->string('title');
            $table->string('phone');
            $table->string('date_bid');
            $table->string('usd_ask');
            $table->string('usd_bid');
            $table->string('eur_ask');
            $table->string('eur_bid');
            $table->string('rub_ask');
            $table->string('rub_bid');

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
        Schema::dropIfExists('organizations');
    }
}
