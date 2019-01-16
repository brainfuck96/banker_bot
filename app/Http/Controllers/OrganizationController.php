<?php

namespace App\Http\Controllers;

use App\Services\showDBListService;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\MainConversation;

class OrganizationController extends Controller
{
    public function mainMenu(BotMan $bot)
    {
        $bot->startConversation(new MainConversation());
    }

    public function ye(BotMan $bot){
        //$str = new showDBListService();
        $bot->reply('aae');//$str->showDB());
    }

}
