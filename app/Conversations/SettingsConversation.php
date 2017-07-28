<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use Mpociot\BotMan\Answer;
use Mpociot\BotMan\Button;
use Mpociot\BotMan\Conversation;
use Mpociot\BotMan\Question;

class SettingsConversation extends Conversation
{
    /**
     * undocumented function
     *
     * @return void
     */
    public function getToken()
    {
        $this->ask('Hi I\'m Tanya, your cool and awesome tanda assistant. to start, type', function (Answer $answer) {
            $token = $answer->getText();
        });

        $question = Question::create('What do you want to do?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Tell a joke')->value('joke'),
                Button::create('Inspiring Quote')->value('quote'),
                Button::create('Time in')->value('timein'),
                Button::create('Time out')->value('timeout'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'joke') {
                    $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                    $this->say($joke->value->joke);
                } else {
                    $this->say(Inspiring::quote());
                }
            }
        });
    }

    public function run()
    {
        $this->getToken();
    }
}
