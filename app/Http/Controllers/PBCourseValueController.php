<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\CurrService;
use App\Http\Controllers\Controller;

class PBCourseValueController extends Controller
{
    public function showCourse($bot){

        $bot->reply($this->currency->getCurr());


    }

    public function __construct(){
        
        $this->currency = new CurrService(); 
    }
}
