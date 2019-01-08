<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CurrService;

class PBCourseValueController extends Controller
{
    public function __construct()
    {

        $this->currency = new CurrService();
    }

    public function showCourse($bot)
    {

        $bot->reply($this->currency->getCurr());

    }

    public function showCourseArhive($bot)
    {

        $bot->reply($this->currency->getCurr());

    }

}
