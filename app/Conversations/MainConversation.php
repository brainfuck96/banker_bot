<?php

namespace App\Conversations;

use App\Conversations\ArhiveOrgDBConverstation;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;

class MainConversation extends Conversation
{

    public function askReason()
    {
        try {
            $question = Question::create("Exchange Rates Ukraine Banks")

                ->addButtons([
                    Button::create('ALL UKR BANKs stable ')->value('all'),
                    Button::create('Arhive PB (from 01.01.2014)')->value('arh'),
                    Button::create('<- BACK ')->value('back'),
                ]);

            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {

                        case 'arh':
                            $this->bot->startConversation(new ArhiveOrgDBConverstation());
                            break;
                        case 'all':
                            $this->bot->startConversation(new AllBakcsDBConverstation());
                            break;
                        case 'back':
                            $this->bot->startConversation(new ExampleConversation());
                            break;
                    }

                } else {
                    $this->say('wrong enter');
                }
            });
        } catch (Exception $e) {

            $this->say('Upsssss )');
        }

    }

    public function run()
    {
        $this->askReason();
    }
}
