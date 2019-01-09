<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Foundation\Inspiring;

class ExampleConversation extends Conversation
{
    /**
     * First question
     */
    public function askReason()
    {
        $question = Question::create("Exchange Rates Ukraine")
        // ->fallback('Unable to ask question')
        // ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Live PB (quick)')->value('curse'),
                Button::create('Arhive')->value('arhive'),
                Button::create('Rates ALL UKR BANKs ')->value('all'),
                Button::create('EXIT ')->value('exit'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'curse':
                        $this->say((new App\Services\CurrService)->getCurr());
                        break;
                    case 'arhive':
                        $this->askData(); 
                        break;
                    case 'all':
                        $this->askAllBanks();
                        break;
                    case 'exit':
                        break;
                }

            } else {
                $this->say(Inspiring::quote());
            }
        });
    }

    public function askData()
    {
        $this->ask('Enter data for example: 19.10.2014 ', function (Answer $answer) {
            $date = $answer->getText();

            $this->say((new App\Services\CurrService)->getArhiveCurr($date));
        });
    }

    public function askAllBanks(){
        $question = Question::create("Choose currice")
            ->addButtons([
                Button::create('USD')->value('usd'),
                Button::create('EUR')->value('eur'),
                Button::create('RUB')->value('rub'),
                Button::create('EXIT ')->value('exit'),
            ]);
            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case 'usd':
                            $this->say((new App\Services\CurrService)->getCurrAll('USD'));
                            break;
                        case 'eur':
                            $this->say((new App\Services\CurrService)->getCurrAll('EUR')); 
                            break;
                        case 'rub':
                            $this->say((new App\Services\CurrService)->getCurrAll('RUB'));
                            break;
                        case 'exit':
                            break;
                    }
                
                    // else {
                    //     $this->say(Inspiring::quote());
                    // }
                
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }
}
