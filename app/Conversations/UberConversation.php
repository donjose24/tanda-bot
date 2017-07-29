<?php

namespace App\Conversations;

use Mpociot\BotMan\Answer;
use Mpociot\BotMan\Button;
use Mpociot\BotMan\Conversation;
use Mpociot\BotMan\Question;

class UberConversation extends Conversation
{
    private $request;
    /**
     * Contructor
     *
     * @return void
     */
    public function __payload($request)
    {
        $this->request = $request;
    }

    public function notifyRider()
    {
        $this->say('Status update from uber: ' + $this->request->meta->status);
    }

    /**
     * Runs the conversation
     *
     * @return void
     */
    public function run($param)
    {
        $this->notifyRider();
    }
}
