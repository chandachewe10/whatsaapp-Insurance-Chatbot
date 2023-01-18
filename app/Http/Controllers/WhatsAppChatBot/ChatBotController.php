<?php

namespace App\Http\Controllers\WhatsAppChatBot;

use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\Controller;
use App\Models\{conversations, motorInsurance};
use Barryvdh\DomPDF\Facade\Pdf;
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

// Check if the last conversation is there and erase everything if it is more than 5 minutes

if($last_conversation){
    $current_time = Carbon::parse(Carbon::now());
    $last_conversation_time = Carbon::parse($last_conversation->updated_at); 
    $diff =  $current_time->diff($last_conversation_time); 
    $diffInMinutes = $diff->i;


    if($diffInMinutes >= 2 ){
     // Start Afresh for this whatsapp line
                   
     $erase = conversations::where('client_whatsapp_number',"=",$from)->get();
     if($erase){
       foreach ($erase as $trash_all){
           $trash_all->delete();
       }
       $message = "You took too long to respond:\n\n";
    
       $message .= "Please type *menu* to return to the main menu";
       
       
       $this->sendWhatsAppMessage($message, $from);      
     }   
    }
}





            if (is_null($last_conversation) ||  $body === 'menu') {
                $message = "Welcome to *MyEliana-Insure*.\n";
                $message .= "Please select an option below\n\n";
                $message .= "1. Buy Motor Insurance\n";
                $message .= "2. Get a Quote\n";
                $message .= "3. View our products\n";
                $message .= "4.  Contact Us\n";
                //$message .= "5.  Contact Us\n";

                    // Start Afresh for this line
                   
              $erase = conversations::where('client_whatsapp_number',"=",$from)->get();
              if($erase){
                foreach ($erase as $trash_all){
                    $trash_all->delete();
                }
                    
              }



                // Opening Message Sent
                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "opening message"
                ]);


                $this->sendWhatsAppMessage($message, $from);
            }
            // Step 1

            if ($request->input('Body') == 1 && $last_conversation->last_conversation === 'opening message' && $diffInMinutes <= 2) {
                $message = "*Motor* Insurance.\n\n";
                $message .= "What's your vehicle registration number? e.g. BAE1010\n\n";
                $message .= "type *menu* to return to the main menu \n";

                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "What's your vehicle registration number? e.g. BAE1010"
                ]);

                $this->sendWhatsAppMessage($message, $from);
            }



            if ($request->input('Body') == 2 && $last_conversation->last_conversation === 'opening message' && $diffInMinutes <= 2) {
                $message = "Get a *QUOTE*.\n";
                $message .= "What are your Full Names?\n\n";
                $message .= "type *menu* to return to the main menu";
                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "What are your Full Names?"
                ]);

                motorInsurance::updateOrCreate([
                    'client_whatsapp_number' => $from
                ], [
                    'client_whatsapp_number' => $from,
                    'client_name' => $body
                ]);




                $this->sendWhatsAppMessage($message, $from);
            }




            if ($request->input('Body') == 3 && $last_conversation->last_conversation === 'opening message' && $diffInMinutes <= 2) {
                $message = "We offer the following products *Insurance*.\n\n";
                $message .= "1. Motor Insurance\n\n";
                $message .= "Please find out more on https://myeliana.com/eliana_insure.html \n\n\n";
                $message .= "Type *menu* to return to the main menu";
                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "Our Insurance products"
                ]);
                $this->sendWhatsAppMessage($message, $from);
            }




            if ($request->input('Body') == 4 && $last_conversation->last_conversation === 'opening message' && $diffInMinutes <= 2) {
                $message = "Please *Contact Us*. on email at: \n";
                $message .= "support@eliana-insure.com\n";
                $message .= "or via phone call at: \n";
                $message .= "0977787178\n\n";
                $message .= "Type *menu* to return to the main menu";
                conversations::create([
                    "client_whatsapp_number" => $from,
                    "last_conversation" => "contact us"
                ]);
                $this->sendWhatsAppMessage($message, $from);
            }


// Buy Motor Insurance
// Motor  : VEHICLE DETAILS
//Year of Manufacture


if ($last_conversation->last_conversation === "What's your vehicle registration number? e.g. BAE1010" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Year of Manufacture:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Year of Manufacture"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}


// Engine Number

if ($last_conversation->last_conversation === "Year of Manufacture" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Engine Number:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Engine Number"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}



// Chassis Number

if ($last_conversation->last_conversation === "Vehicle Engine Number" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Chassis Number:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Chassis Number"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}


// Vehicle Maker

if ($last_conversation->last_conversation === "Vehicle Chassis Number" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Maker:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Maker"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}


// Vehicle Model

if ($last_conversation->last_conversation === "Vehicle Maker" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Model:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Model"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}



// Vehicle Color

if ($last_conversation->last_conversation === "Vehicle Model" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Color:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Color"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}



// Motor  : POLICY DETAILS
// Insured Name
if ($last_conversation->last_conversation === "Vehicle Color" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "What is the Vehicle Insured Name?:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Insured Name"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}



// Cover Type
if ($last_conversation->last_conversation === "Vehicle Insured Name" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Select Vehicle Cover Type:\n\n";
    $message .= "1. Comprehensive \n";
    $message .= "2. Full Third Party \n\n";
    $message .= "Type *menu* to return to the main menu ";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Cover Type"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}



// Vehicle Type
if ($last_conversation->last_conversation === "Vehicle Cover Type" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Select the Vehicle Type:\n\n";
    $message .= "1. Private \n";
    $message .= "2. Commercial \n";
    $message .= "3. Bus/Tax \n\n";
    $message .= "Type *menu* to return to the main menu ";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Vehicle Type"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}




// Number of Quarters
if ($last_conversation->last_conversation === "Vehicle Type" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Number of Quarters:\n\n";
    
    $message .= "Type *menu* to return to the main menu ";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Number of Quarters"
    ]);
    $this->sendWhatsAppMessage($message, $from);
}











// Get Motor Insurance Quatation
// Motor  : VEHICLE DETAILS
//Year of Manufacture

if ($last_conversation->last_conversation === "What are your Full Names?" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "What's your vehicle registration number? e.g. BAE1010:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_What's your vehicle registration number? e.g. BAE1010"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'vehicle_registration_number' => $body
    ]);


    $this->sendWhatsAppMessage($message, $from);
}





if ($last_conversation->last_conversation === "Quatation_What's your vehicle registration number? e.g. BAE1010" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Year of Manufacture:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Year of Manufacture"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'vehicle_manufacture_year' => $body
    ]);

    $this->sendWhatsAppMessage($message, $from);
}


// Engine Number

if ($last_conversation->last_conversation === "Quatation_Year of Manufacture" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Engine Number:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Vehicle Engine Number"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'vehicle_engine_number' => $body
    ]);

    $this->sendWhatsAppMessage($message, $from);
}



// Chassis Number

if ($last_conversation->last_conversation === "Quatation_Vehicle Engine Number" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Chassis Number:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Vehicle Chassis Number"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'vehicle_chassis_number' => $body
    ]);


    $this->sendWhatsAppMessage($message, $from);
}


// Vehicle Maker

if ($last_conversation->last_conversation === "Quatation_Vehicle Chassis Number" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Maker:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Vehicle Maker"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'vehicle_maker' => $body
    ]);

    $this->sendWhatsAppMessage($message, $from);
}


// Vehicle Model

if ($last_conversation->last_conversation === "Quatation_Vehicle Maker" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Model:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Vehicle Model"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'vehicle_model' => $body
    ]);


    $this->sendWhatsAppMessage($message, $from);
}



// Vehicle Color

if ($last_conversation->last_conversation === "Quatation_Vehicle Model" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Vehicle Color:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Vehicle Color"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'vehicle_color' => $body
    ]);


    $this->sendWhatsAppMessage($message, $from);
}



// Motor  : POLICY DETAILS
// Insured Name
if ($last_conversation->last_conversation === "Quatation_Vehicle Color" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "What is the Vehicle Insured Name?:\n\n";
    $message .= "Type *menu* to return to the main menu \n";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Vehicle Insured Name"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'vehicle_insured_name' => $body
    ]);


    $this->sendWhatsAppMessage($message, $from);
}



// Cover Type
if ($last_conversation->last_conversation === "Quatation_Vehicle Insured Name" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Select Vehicle Cover Type:\n\n";
    $message .= "1. Comprehensive \n";
    $message .= "2. Full Third Party \n\n";
    $message .= "Type *menu* to return to the main menu ";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Vehicle Cover Type"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'insurance_type' => $body
    ]);


    $this->sendWhatsAppMessage($message, $from);
}



// Vehicle Type
if ($last_conversation->last_conversation === "Quatation_Vehicle Cover Type" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Select the Vehicle Type:\n\n";
    $message .= "1. Private \n";
    $message .= "2. Commercial \n";
    $message .= "3. Bus/Tax \n\n";
    $message .= "Type *menu* to return to the main menu ";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Vehicle Type"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'vehicle_type' => $body
    ]);


    $this->sendWhatsAppMessage($message, $from);
}




// Number of Quarters
if ($last_conversation->last_conversation === "Quatation_Vehicle Type" && $body != "menu" && $diffInMinutes <= 2) {
    $message = "Enter Number of Quarters:\n\n";
    
    $message .= "Type *menu* to return to the main menu ";
    
    conversations::create([
        "client_whatsapp_number" => $from,
        "last_conversation" => "Quatation_Number of Quarters"
    ]);

    motorInsurance::updateOrCreate([
        'client_whatsapp_number' => $from
    ], [
        'client_whatsapp_number' => $from,
        'quarter' => $body
    ]);


    $this->sendWhatsAppMessage($message, $from);
}




// Compose Quotation
if ($last_conversation->last_conversation === "Quatation_Number of Quarters" && $body != "menu" && $diffInMinutes <= 2) {


    
    
    

    $data_submitted = motorInsurance::where('client_whatsapp_number',"=",$from)->first();
    if($data_submitted){
        $pdf = PDF::loadView("Quatation.Quatation", [
            'data_submitted' => $data_submitted
        ])
        ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled' => true]);
        
        $message = "Here is your Quatation:\n\n";    
        $message .= "Type *menu* to return to the main menu ";
        $message .= $pdf->output();
                    $this->sendWhatsAppAttachmentMessage($message, $from);
    }
     


    
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



/**
     * Sends a WhatsApp Attachment Message to user using
     * @param string $message MediaUrl of sms
     * @param string $recipient Number of recipient
     */
    public function sendWhatsAppAttachmentMessage(string $message, string $recipient)
    {
        $twilio_whatsapp_number = getenv('TWILIO_WHATSAPP_NUMBER');
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");

        $client = new Client($account_sid, $auth_token);
        return $client->messages->create($recipient, array('mediaUrl' => $message,'from' => "whatsapp:$twilio_whatsapp_number"));
    }
}












