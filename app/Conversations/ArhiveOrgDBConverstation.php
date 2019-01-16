<?php

namespace App\Conversations;

use App\Services\CurrAllBanksService;
use App\Services\CurrService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;
use Illuminate\Foundation\Inspiring;

class ArhiveOrgDBConverstation extends Conversation
{

    public static $data;
   // protected $myear;
    public function askDataArhive()
    {
        $question = Question::create('ENTER YEAR  2014 - 2018');

        return $this->ask($question, function (Answer $answer) {
            $year = $answer->getText();
            if ($year >= 2014 && $year <= 2018) {
                self::$data = [
                    'year' => $year,
                ];
                $this->askMonth();
            } else {
                $this->askAgain();
            }
        });

    }


    public function askMonth()
    {
//        $this->myear = $year;

//
             $this->ask('ENTER MOUTH  1 - 12 ', function (Answer $answer) {
                $month = $answer->getText();
                if ($month >= 1 && $month <= 12) {
                    self::$data = [
                        'month' => $month,
                    ];
//                    $mydata = self::$data;
                    $this->askDay();//say("your data m: ");//askDay();
                } else {
                    $this->askAgain();
                }
            });
           // $this->say((new App\Services\CurrService)->getArhiveCurr($this->data));
    }

    public function askDay()
    {
//        $this->myear = $year;

//
        $this->ask('ENTER DAY  1 - 30 ', function (Answer $answer) {
            $day = $answer->getText();
            if ($day >= 1 && $day <= 30) {
                    self::$data = [
                        'day' => $day
                    ];
//                    $mydata = self::$data;
              //  $data = ''.self::$data['day'].'.'.self::$data['month'].'.'.self::$data['year'];
                $data  = self::$data['day'];
  /*test*/      $this->say("your data $data");
//                $this->say((new CurrService())->getArhiveCurr($data));
                } else {
                $this->askAgain();
            }
        });
    }


    public  function askAgain(){

        $question = Question::create('Error Incorrect Enter... Please try again')
            ->addButtons([
                Button::create('AGAIN?')->value('confirm'),
                Button::create('EXIT')->value('exit'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'confirm':
                        $this->askDataArhive();
                        break;
                    case 'exit':
                        break;
                }

            }
            else $this->say('Sorry this is ERROR');
        });

    }
//    public function askBank()
//    {
//        try {
//            $banks = new CurrAllBanksService();
//            $list = $banks->getAllBanksList();
//
//            $question = Question::create("Choose organization");
//            foreach ($list as $org) {
//                $question->addButtons(
//                    [Button::create($org->title)->value($org->id)]
//                );
//            }
//            return $this->ask($question, function (Answer $answer) {
//                if ($answer->isInteractiveMessageReply()) {
//                    $this->say(
//                        (new App\Services\CurrAllBanksService)->getSomeBank($answer->getValue())
//                    );
//                }
//            });
//
//        } catch (Exception $e) {
//
//            $this->say(Inspiring::quote());
//        }
//
//    }
// public function askAgain($func)
    // {
    //     $question = Question::create("again?")->addButtons(
    //         [Button::create('Continue')->value('continue'),
    //             Button::create('EXIT')->value('exit'),
    //         ]);
    //     return $this->ask($question, function (Answer $answer) {
    //         if ($answer->isInteractiveMessageReply()) {
    //             switch ($answer->getValue()) {
    //                 case 'continue':
    //                     $this->$func;
    //                     break;
    //                 case 'exit':
    //                     break;}
    //         }else {
    //             $this->say(Inspiring::quote());
    //         }
    //     });

    // }
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
        $this->askDataArhive();
    }
}
