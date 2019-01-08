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
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'curse':
                        $this->say((new App\Services\CurrService)->getCurr());
                        break;
                    case 'arhive':
                        $this->askData(); //say((new App\Services\CurrService)->getCurr());
                        break;
                    case 'all':
                        $this->say((new App\Services\CurrService)->getCurr());
                        break;
                }
                // if ($answer->getValue() === 'joke') {
                //     $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                //     $this->say($joke->value->joke);
            } else {
                $this->say(Inspiring::quote());
            }
            //}
        });
    }

    public function askData()
    {
        $this->ask('Enter data example  "19.10.2014" ', function (Answer $answer) {
            $date = $answer->getText();

            $this->say((new App\Services\CurrService)->getArhiveCurr($date));
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
