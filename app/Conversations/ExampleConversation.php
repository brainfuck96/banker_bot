<?php

namespace App\Conversations;

use App\Services\CurrAllBanksService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;
use Illuminate\Foundation\Inspiring;

class ExampleConversation extends Conversation
{
    /**
     * First question
     */
    public function askReason()
    {
        $question = Question::create("Exchange Rates Ukraine Banks")
        // ->fallback('Unable to ask question')
        // ->callbackId('ask_reason')
            ->addButtons([
                Button::create('PrivatBank (quick)')->value('curse'),
                Button::create('ALL UKR BANKs (Live) ')->value('all'),
                Button::create('Arhive PB (from 01.01.2014)')->value('arhive'),
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
                        $this->askBank();
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

    public function askBank()
    {
        try {
            $banks = new CurrAllBanksService();
            $list = $banks->getAllBanksList();

            $question = Question::create("Choose organization");
            foreach ($list as $org) {
                $question->addButtons(
                    [Button::create($org->title)->value($org->oldId),]
                );
            }
            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    $this->say(
                        (new App\Services\CurrAllBanksService)->getSomeBank($answer->getValue())
                    );
                }
            });

        } catch (Exception $e) {

            $this->say(Inspiring::quote());
        }

    }

    // public function askAllBanks()
    // {
    //     $question = Question::create("Choose currice")
    //         ->addButtons([
    //             Button::create('ALL BANKs LIST')->value('list'),
    //             Button::create('USD')->value('usd'),
    //             Button::create('EUR')->value('eur'),
    //             Button::create('RUB')->value('rub'),
    //             Button::create('EXIT ')->value('exit'),
    //         ]);
    //     return $this->ask($question, function (Answer $answer) {
    //         if ($answer->isInteractiveMessageReply()) {
    //             switch ($answer->getValue()) {
    //                 case 'list':
    //                     $this->askBank();
    //                     break;
    //                 case 'usd':
    //                     $this->say((new App\Services\CurrAllBanksService)->getSomeBank('USD'));
    //                     break;
    //                 case 'eur':
    //                     $this->say((new App\Services\CurrAllBanksService)->getSomeBank('EUR'));
    //                     break;
    //                 case 'rub':
    //                     $this->say((new App\Services\CurrAllBanksService)->getSomeBank('RUB'));
    //                     break;
    //                 case 'exit':
    //                     break;
    //             }

    //             // else {
    //             //     $this->say(Inspiring::quote());
    //             // }

    //         }
    //     });
    // }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }
}
