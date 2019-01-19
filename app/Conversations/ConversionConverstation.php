<?php

namespace App\Conversations;

use App\Conversion;
use App\Organization;
use App\Services\CurrService;
use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;

class ConversionConverstation extends Conversation
{

    public function askBYorSale()
    {
        $user = User::firstOrCreate([
            'chat_id' => $this->bot->getUser()->getId()]);

        $cur_val = Conversion::firstOrCreate([
            'user_id' => $user->id]);

        $question = Question::create("Menu Conversion")

            ->addButtons([
                Button::create('I want BUY ')->value('sale'),
                Button::create('I want SALE')->value('buy'),
                Button::create('<- BACK ')->value('back'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue() == 'back'){
                    $this->bot->startConversation(new ExampleConversation());
                }else{
                    try{
                        $user = User::updateOrCreate([
                            'chat_id' => $this->bot->getUser()->getId()]);

                        $cur_val = Conversion::updateOrCreate([
                            'user_id' => $user->id],
                            ['ask' => $answer->getValue(),
                            ]);

                        $cur_val->save();

                        $this->askCurr();
                    }catch (Exception $e) {

                        $this->say('Sorry somthen wrong');
                    }

                }

            } else {
                $this->say("Sorry I'm understand.... Try enter Bottoms... ");
            }
        });
    }

    public function askCurr()//($value)
    {

        $arr_cur = (new CurrService())->getColCurr();

        $question = Question::create('CHOOSE CURRENCI  ');

        foreach ($arr_cur as $cur) {

            $question->addButtons(
                [Button::create($cur)->value($cur)//value(($cur)+$value)
              ]);
        }

        $question->addButtons([Button::create('<- BACK')->value('back')]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'back') {
                    $this->bot->startConversation(new ExampleConversation());
                } else {
                    //$ans = $answer->getValue();
                    try {
                        $user = User::updateOrCreate([
                            'chat_id' => $this->bot->getUser()->getId()]);

                        $cur_val = Conversion::updateOrCreate([
                            'user_id' => $user->id],
                            ['cur' => $answer->getValue(),
                            ]);
//
                        $cur_val->save();

                        $this->convDialog();
                    } catch (Exception $e)
                    {
                        $this->say('Sorry somthen wrong');
                    }
                }
            }
        });

    }


    public function convDialog(){

        $this->ask('Enter numbers:', function (Answer $answer){

            try{
                $user = User::updateOrCreate([
                    'chat_id' => $this->bot->getUser()->getId()]);

                $cur_val = Conversion::updateOrCreate([
                    'user_id' => $user->id],
                    ['temp' => $answer->getText(),
                    ]);
//
                $cur_val->save();

                $this->say((new CurrService())->getConversValue($cur_val->temp, $cur_val->cur, $cur_val->ask));
            }catch (Exception $e)
            {
                $this->say('Sorry wrong numbers .... Try enter numbers and \'.\'for example 100 or 200.30 ');

            }

        });

    }


    /**
     * Start the conversation
     */
    public function run()
    {
        //$this->askBank();
        $this->askBYorSale();
    }
}
