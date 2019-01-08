<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrService;

class PBCourseValue extends Controller
{
    public function showCourse($bot){

        $bot->reply($this->currency);


    }

    public function __construct(){
        
        $this->currency = new CurrService(); 
    }
}
