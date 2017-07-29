<?php

namespace App\Conversations;

use App\Account;
use Illuminate\Foundation\Inspiring;
use Mpociot\BotMan\Answer;
use Mpociot\BotMan\Button;
use Mpociot\BotMan\Conversation;
use Mpociot\BotMan\Question;
use App\Utilities\TandaApi;
use Session;
use Mpociot\BotMan\Attachments\Location;

class SettingsConversation extends Conversation
{
    /**
     * Makes Menu
     *
     * @return void
     */
    public function getToken()
    {
        $bot = $this->bot;
        if (Account::where('user_id', $bot->getUser()->getId())->count() == 0) {
            $user = $bot->getUser();

            $account = new Account();
            $account->user_id = $user->getId();
            $account->first_name = $user->getFirstName();
            $account->last_name = $user->getLastName();

            $account->save();
            $this->ask('Hi! I\'m Tanya, your cool and awesome tanda assistant. to start, Since you are a first time user, please enter your tanda token: ', function (Answer $answer) use ($account) {
                $token = $answer->getText();
                $account->token = $token;
                $account->save();
                $this->askQuestions($token);
            });
        } else {
            $user = Account::where('user_id', $bot->getUser()->getId())->first();
            $this->say('Welcome back!');
            $this->askQuestions($user->token);
        }
    }

    /**
     * Asks questions
     *
     * @return void
     */
    public function askQuestions($token)
    {

        $question = Question::create('What do you want to do?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Tell a joke')->value('joke'),
                Button::create('Inspiring Quote')->value('quote'),
                Button::create('Time in')->value('timein'),
                Button::create('Time out')->value('timeout'),
                Button::create('Bye')->value('Yoko na'),
            ]);

        $this->ask($question, function (Answer $answer) use ($token) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'joke') {
                    $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                    $this->say($joke->value->joke);
                } else if ($answer->getValue() === 'timein') {
                    $api = new TandaApi($token);
                    $api->clockIn();
                    $this->say("Gotcha!");
                } else if ($answer->getValue() === 'timeout') {
                    $api = new TandaApi($token);
                    $api->clockOut();
                    $this->say("Take Care!");
                } else if ($answer->getValue() === 'quote') {
                    $this->say(Inspiring::quote());
                } else {
                    $this->say('Bye!');
                }
            }
        });
    }


    /**
     * Run conversation
     */
    public function run()
    {
        //$this->getToken();
        $this->askLocation();
    }

    /**
     * Ask for location
     */
    public function askLocation()
    {
        $this->askForLocation('Please share your location:', function (Location $location) {
            $this->say('Received: '.print_r($location, true));
        }, null, [
            'message' => [
                'quick_replies' => json_encode([
                    [
                        'content_type' => 'location'
                    ]
                ])
            ]
        ]);
    }
}
