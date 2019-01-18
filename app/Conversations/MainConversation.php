<?php

namespace App\Conversations;

use App\Organization;
use App\Services\CurrAllBanksService;
use App\Services\showDBListService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;
use Illuminate\Foundation\Inspiring;
use App\Conversations\ArhiveOrgDBConverstation;

class MainConversation extends Conversation{

    public function askReason()
    {
        try{
            $question = Question::create("Exchange Rates Ukraine Banks")
                // ->fallback('Unable to ask question')
                // ->callbackId('ask_reason')
                ->addButtons([
                   // Button::create('PrivatBank (quick)')->value('curse'),
                    Button::create('ALL UKR BANKs  ')->value('all'),
                    Button::create('Arhive PB (from 01.01.2014)')->value('arh'),
                    Button::create('EXIT ')->value('exit'),
                ]);

            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
//                        case 'curse':
//                            $this->say((new App\Services\CurrService)->getCurr());
//                            break;
                        case 'arh':
                            $this->bot->startConversation(new ArhiveOrgDBConverstation());
                            break;
                            case 'all':
                            $this->bot->startConversation(new AllBakcsDBConverstation());
                        break;
                        case 'exit':
                            break;
                    }

                } else {
                    $this->say('wrong enter');
                }
            });
        }
        catch (Exception $e) {

            $this->say('Upsssss )');
        }

    }

    public function run()
    {
        $this->askReason();
    }
}