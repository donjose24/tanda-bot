<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversations\UberConversation;
use Mpociot\BotMan\Drivers\FacebookDriver;

class UberController extends Controller
{
    /**
     * Process Webhook
     *
     * @return void
     */
    public function process(Request $request)
    {
        $resource = $request->get('meta')->resource_id;
        $user = Account::where('uber_request_id', $resource)->firstOrFail();

        $botman = resolve('botman');
        $botman->startConversation(new UberConversation($request->all()), $user->user_id, FacebookDriver::class);
    }
}
