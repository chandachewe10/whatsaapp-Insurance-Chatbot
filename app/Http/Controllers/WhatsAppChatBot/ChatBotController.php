<?php

namespace App\Http\Controllers\WhatsAppChatBot;

use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\Controller;
use App\Models\conversations;
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
            $last_conversation = conversations::where('client_whatsapp_number', "=", $from)->latest()->first();
            if (is_null($last_conversation) ||  $body === 'menu') {
                $message = "Welcome to *MyEliana-Insure*.\n";
                $message .= "Please select an option below\n\n";
                $message .= "1. Validate Motor Insurance\n";
                $message .= "2. Get a Quote\n";
                $message .= "3. View our products\n";
                $message .= "4.  Contact Us\n";
                //$message .= "5.  Contact Us\n";

                // Opening Message Sent
                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "opening message"
                ]);


                $this->sendWhatsAppMessage($message, $from);
            }
            // Step 1

            if ($request->input('Body') == 1 && $last_conversation->last_conversation === 'opening message') {
                $message = "*Motor* Insurance.\n\n";
                $message .= "What's your vehicle registration number? e.g. BAE1010\n\n";
                $message .= "type *menu* to return to the main menu \n";

                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "What's your vehicle registration number? e.g. BAE1010"
                ]);

                $this->sendWhatsAppMessage($message, $from);
            }



            if ($request->input('Body') == 2 && $last_conversation->last_conversation === 'opening message') {
                $message = "Get a *QUOTE*.\n";
                $message .= "What are your Full Names?\n\n";
                $message .= "type *menu* to return to the main menu";
                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "What are your Full Names?"
                ]);
                $this->sendWhatsAppMessage($message, $from);
            }




            if ($request->input('Body') == 3 && $last_conversation->last_conversation === 'opening message') {
                $message = "We offer the following products *Insurance*.\n";
                $message .= "1. Motor Insurance\n\n";
                $message .= "Please find out more on https://myeliana.com/eliana_insure.html \n\n\n";
                $message .= "type *menu* to return to the main menu";
                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "Our Insurance products"
                ]);
                $this->sendWhatsAppMessage($message, $from);
            }




            if ($request->input('Body') == 4 && $last_conversation->last_conversation === 'opening message') {
                $message = "Please *Contact Us*. on email at: \n";
                $message .= "support@eliana-insure.com\n";
                $message .= "or via phone call at: \n";
                $message .= "0977787578\n\n";
                $message .= "type *menu* to return to the main menu";
                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "contact us"
                ]);
                $this->sendWhatsAppMessage($message, $from);
            }



// Motor  : VEHICLE DETAILS
//Year of Manufacture


if ($last_conversation->last_conversation === "What's your vehicle registration number? e.g. BAE1010") {
    $message = "Year of Manufacture:\n\n";
    $message .= "type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Year of Manufacture"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}


// Engine Number

if ($last_conversation->last_conversation === "Year of Manufacture") {
    $message = "Vehicle Engine Number:\n\n";
    $message .= "type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Engine Number"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}



// Chassis Number

if ($last_conversation->last_conversation === "Vehicle Engine Number") {
    $message = "Vehicle Chassis Number:\n\n";
    $message .= "type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Chassis Number"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}


// Vehicle Maker

if ($last_conversation->last_conversation === "Vehicle Chassis Number") {
    $message = "Vehicle Maker:\n\n";
    $message .= "type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Maker"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}


// Vehicle Model

if ($last_conversation->last_conversation === "Vehicle Maker") {
    $message = "Vehicle Model:\n\n";
    $message .= "type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Model"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}



// Vehicle Color

if ($last_conversation->last_conversation === "Vehicle Model") {
    $message = "Vehicle Color:\n\n";
    $message .= "type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Color"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}









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
