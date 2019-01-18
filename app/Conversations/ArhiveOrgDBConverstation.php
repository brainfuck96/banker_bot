<?php

namespace App\Conversations;

use App\DataArh;
use App\PrivatBankArhive;

use App\Services\CurrAllBanksService;
use App\Services\CurrService;
use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;
use function GuzzleHttp\Promise\all;
use Illuminate\Foundation\Inspiring;

class ArhiveOrgDBConverstation extends Conversation
{


    public function askDataArhive()
    {
        $user = User::firstOrCreate([
            'chat_id'=>$this->bot->getUser()->getId()]);

        $data = DataArh::firstOrCreate([
            'user_id'=>$user->id,]);

            $years = $this->rangeData(2014, 2018);
            $question = Question::create('ENTER YEAR  ');

            foreach ($years as $year) {
                $question->addButtons(
                    [Button::create($year)->value($year)] );
            }

            $question->addButtons([Button::create('<- BACK')->value('back')]);

            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if ($answer->getValue() == 'back') {
                        $this->bot->startConversation(new MainConversation());
                    } else {
                             try{

                                 $user = User::updateOrCreate([
                                     'chat_id'=>$this->bot->getUser()->getId()]);

                                 $data = DataArh::updateOrCreate([
                                     'user_id'=>$user->id],
                                     ['year' => $answer->getValue(),
                                 ]);
//
                                 $data->save();
                                // $user()->save();


                                 $this->askMonth();
                                 }

                                 catch (Exception $e) {

                                        $this->say('error data');
                             }
                    }
                }
            });




    }

    public function rangeData($begin, $end){

        $arr = [];
        foreach (range($begin, $end) as $num){
            $arr[] = $num;
        }

        return $arr;
    }


    public function askMonth()
    {

        //$data['year'] = $tempdata['year'];
        $arr_months = ['January', 'February', 'March', 'April',
                        'May', 'June', 'July ', 'August',
                       'September', 'October', 'November', 'December',];

          $months = array_combine($this->rangeData(1, 12), $arr_months);

        $question = Question::create('ENTER MONTH  ');

        foreach ($months as $key_month=>$name_month) {
            $question->addButtons(
                [Button::create($name_month)->value($key_month)] );
        }

        $question->addButtons([Button::create('<- BACK')->value('back')]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'back') {
                    $this->bot->startConversation(new ArhiveOrgDBConverstation());
                } else {


                    try{
                        $user = User::updateOrCreate([
                            'chat_id'=>$this->bot->getUser()->getId()]);
//
                        $data = DataArh::updateOrCreate(
                            ['user_id'=>$user->id],
                        [
                            'month' => $answer->getValue(),
                        ]);
                        $data->save();

                        $this->askDay();
                    }catch (Exception $e) {

                        $this->say('error day_method ');

                    }


                }
            }
        });

    }

    public function askDay()
    {

       $days = $this->rangeData(1, 31);

        $question = Question::create('ENTER DAY  ');

        foreach ($days as $day) {
            $question->addButtons(
                [Button::create($day)->value($day)] );
        }

        $question->addButtons([Button::create('<- BACK')->value('back')]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'back') {
                    $this->bot->startConversation(new ArhiveOrgDBConverstation());
                } else {


                    $user = User::updateOrCreate([
                        'chat_id'=>$this->bot->getUser()->getId()]);
//
                    $data = DataArh::updateOrCreate(
                        ['user_id'=>$user->id],
                        [
                            'day' => $answer->getValue(),
                        ]);
                    $data->save();

                    $url = ''.$data->day.'.'.$data->month.'.'.$data->year;
                   // $this->say($url);
                    try{$this->say((new App\Services\CurrService)->getArhiveCurr($url));}
                    catch (Exception $e) {

                        $this->say('error day_method ');
                        // $data->delete();
                    }

                }
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

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askDataArhive();
    }
}
