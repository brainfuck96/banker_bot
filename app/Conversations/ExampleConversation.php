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

            ->addButtons([
                Button::create('PrivatBank (quick)')->value('curse'),
                Button::create('ALL UKR BANKs (Live) ')->value('all'),
                Button::create('More ...')->value('more'),
                Button::create('EXIT ')->value('exit'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'curse':
                        $this->say((new App\Services\CurrService)->getCurr());
                        break;
                    case 'more':
                        $this->bot->startConversation(new MainConversation());
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

    public function askBank()
    {
        try {
            $banks = new CurrAllBanksService();
            $list = $banks->getAllBanksList();

            $question = Question::create("Choose organization");
            foreach ($list as $org) {
                $question->addButtons(
                    [Button::create($org->title)->value($org->id)]
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

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }
}
