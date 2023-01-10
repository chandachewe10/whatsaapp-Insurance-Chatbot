<?php

namespace App\Http\Controllers\WhatsAppChatBot;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
class ChatBotController extends Controller
{
    //
    public function listenToReplies(Request $request)
    {
        $from = $request->input('From'); // Client WhatsApp Number
        $body = $request->input('Body'); // Client Message

        $client = new \GuzzleHttp\Client();
        try {
           
                $this->sendWhatsAppMessage("Recieved Here", $from);
            } 
            
            catch (RequestException $th) {
            $response = json_decode($th->getResponse()->getBody());
            $this->sendWhatsAppMessage($response->message, $from);
        }
        return;
    }

    /**
     * Sends a WhatsApp message  to user using
     * @param string $message Body of sms
     * @param string $recipient Number of recipient
     */
    public function sendWhatsAppMessage(string $message, string $recipient)
    {
        $twilio_whatsapp_number = getenv('TWILIO_WHATSAPP_NUMBER');
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");

        $client = new Client($account_sid, $auth_token);
        return $client->messages->create($recipient, array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
    }
}
