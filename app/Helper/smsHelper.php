<?php
 function registerCode(){
     if (env('APP_ENV') == 'local') {
         return 1234;
     }
}
function getLanguages(){
    return \Carbon\Language::pluck('iso')->toArray();
}



































//require_once '/path/to/vendor/autoload.php';
/*
use Twilio\Rest\Client;

// Find your Account SID and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure
$sid = getenv("TWILIO_ACCOUNT_SID");
$token = getenv("TWILIO_AUTH_TOKEN");


$client = new Twilio\Rest\Client($sid, $token);
$message = $client->messages->create(
    '8881231234', // Text this number
    [
        'from' => '9991231234', // From a valid Twilio number
        'body' => 'Hello from Twilio!'
    ]
);

print $message->sid;



$receiverNumber = "RECEIVER_NUMBER";
$message = "This is testing from ItSolutionStuff.com";

try {

    $account_sid = getenv("TWILIO_SID");
    $auth_token = getenv("TWILIO_TOKEN");
 //   $twilio_number = getenv("TWILIO_FROM");
    $twilio_number = '0597381159';
    $client = new Client($account_sid, $auth_token);
    $client->messages->create($receiverNumber, [
        'from' => $twilio_number,
        'body' => $message]);

     dd('SMS Sent Successfully.');

     } catch (Exception $e) {
     dd("Error: ". $e->getMessage());
    }
*/

