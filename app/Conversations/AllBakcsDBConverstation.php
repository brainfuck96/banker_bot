<?php

namespace App\Conversations;

use App\Organization;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;

class AllBakcsDBConverstation extends Conversation
{

    public function AllBanksMenu()
    {

        $question = Question::create("Exchange Rates Ukraine Banks")

            ->addButtons([
                Button::create('LIST')->value('list'),
                Button::create('BEST OFFER ')->value('best'),
                Button::create('<- BACK ')->value('back'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'list':
                        $this->askBank();
                        break;
                    case 'best':

                        try {
                            $this->bestOfferAskCur();
                        } catch (Exception $e) {

                            $this->say('Sorry somthen wrong');
                        }

                        break;
                    case 'back':
                        $this->bot->startConversation(new MainConversation());
                        break;
                }

            } else {
                $this->say("Sorry I'm understand.... Try enter Bottoms... ");
            }
        });
    }

    public function bestOfferAskCur()
    {

        $arr_cur = ['USD' => 'usd_', 'EUR' => 'eur_', 'RUB' => 'rub_'];

        $question = Question::create('CHOOSE CURRENCI  ');

        foreach ($arr_cur as $cur => $val_cur) {
            $question->addButtons(
                [Button::create($cur)->value($val_cur)]);
        }

        $question->addButtons([Button::create('<- BACK')->value('back')]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'back') {
                    $this->bot->startConversation(new AllBakcsDBConverstation());
                } else {
                    $ans = $answer->getValue();
                    try {
                        $this->say($this->bestOffer($ans));
                    } catch (Exception $e) {
                        $this->say('Sorry somthen wrong');
                    }
                }
            }
        });

    }

    public function bestOffer($value)
    {

        $str_ask = $value . 'ask';
        $str_bid = $value . 'bid';

        $course = mb_strtoupper(trim($value, '_'));

        $result = $this->showOffer($str_ask, $course, 'BUY');
        $result .= $this->showOffer($str_bid, $course, 'SALE');

        return $result;
    }

    private function showOffer($str, $course, $ask)
    {

        $result = "********* Best Course for $ask $course **********" . PHP_EOL;
        $orgazations = Organization::all();
        if ($ask == 'BUY') {
            $orgazations = $orgazations->sortBy($str)->take(10);
        } else {

            $orgazations = $orgazations->sortByDesc($str)->take(10);
        }

        foreach ($orgazations as $bank) {
            $result .= '-----------------------------' . PHP_EOL;
            $result .= $bank->title . "  phone: (" . $bank->phone . ")" . PHP_EOL;
            $result .= "Course $course" . PHP_EOL;
            $result .= "  " . round($bank->$str, 2) . " UAH" . PHP_EOL;
            $result .= '' . PHP_EOL;
            // $result .= " INFO (Data Update - ".$bank->date_bid.")".PHP_EOL;
        }

        return $result;
    }

    public function askBank()
    {

        $organization = Organization::all();

        $question = Question::create('Choose Bank  ');

        foreach ($organization as $bank) {
            $question->addButtons(
                [Button::create($bank->title)->value($bank->id)]);
        }

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $ans = $answer->getValue();

                try {
                    $this->say($this->showCourse($ans));
                } catch (Exception $e) {

                    $this->say('Sorry somthen wrong');
                }

            } else {
                $this->say("Sorry I'm understand.... Try enter Bottoms... ");
            }
        });

    }

    public function showCourse($org)
    {

        $organization = Organization::find($org);
        $result = $organization->title . ' phone: (' . $organization->phone . ')' . PHP_EOL;
        $result .= "******************" . PHP_EOL;
        $result .= "Course USD" . PHP_EOL;
        $result .= "Buy " . round($organization->usd_ask, 2) . " UAH" . PHP_EOL;
        $result .= "Sale " . round($organization->usd_bid, 2) . " UAH" . PHP_EOL;
        $result .= "******************" . PHP_EOL;
        $result .= "Course EUR" . PHP_EOL;
        $result .= "Buy " . round($organization->eur_ask, 2) . " UAH" . PHP_EOL;
        $result .= "Sale " . round($organization->eur_bid, 2) . " UAH" . PHP_EOL;
        $result .= "******************" . PHP_EOL;
        $result .= "Course RUB" . PHP_EOL;
        $result .= "Buy " . round($organization->rub_ask, 2) . " UAH" . PHP_EOL;
        $result .= "Sale " . round($organization->rub_bid, 2) . " UAH" . PHP_EOL;
        $result .= " " . PHP_EOL;
        $result .= " INFO (Data Update - " . $organization->date_bid . ")" . PHP_EOL;

        return $result;

    }

    /**
     * Start the conversation
     */
    public function run()
    {
        //$this->askBank();
        $this->AllBanksMenu();
    }
}
