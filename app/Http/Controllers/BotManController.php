<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle(Request $request)
    {
        $botman = app('botman');
        $botman->verifyServices(env('TOKEN_VERIFY'));

        // Simple respond method
        $botman->hears('Hello', function (BotMan $bot) {
            $bot->reply('Hi I\'m Tanya, your cool and awesome tanda assistant. to start, type /create [tanda_access_token]');
        });

        $botman->hears('/create', function (BotMan $bot) use ($request) {
            $bot->reply(var_dump($request));
        });

        $botman->listen();
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
