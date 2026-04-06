<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Sms extends Eloquent
{

    public static function curlRequestSms($mobile, $text)
    {
    
        $mobile = "91".$mobile;
        $curl = curl_init();

        $text = rawurlencode($text);

     $url = 'https://smsapp.mx9.in/smpp/?username=rainbowh&password=cDDBNQd0Z&from=RAINBO&to='.$mobile.'&text='.$text;

     // $url ="http://smsapp.mx9.in/smpp/?username=rainbowh&password=cDDBNQd0Z&from=RAINBO&to=919815019300&text=Your%20Payment%20Rs%201233Transaction%20No%202922u2h2929229223%20for%20Appointment%20NumberBCHOP-005181%20at%20kondapure%20has%20been%20received.%20Team%20Rainbow";

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
       
        return $response;
    }

    public function appointment_cancellation($mobile,$name,$branch,$time)
    {
       //VC / PC - Cancellation
       //Dear Shravya, your appointment with Rainbow Children Medicare Pvt Ltd in Banjara hills at 5:00 PM has been cancelled.
        $text = "Dear ".$name.", your appointment with Rainbow Children Medicare Pvt Ltd in ".$branch." at ".$time." has been cancelled.";
        return Sms::curlRequestSms($mobile,$text);
    }
    public static function video_consultation_reminder($mobile,$time)
    {
        //VC - Remainder
        $text = "Remainder!!! Your VC has been scheduled at ".$time.", Please be ready to take consultation. Rainbow Children Medicare Pvt Ltd.";
        return Sms::curlRequestSms($mobile,$text);
    }
    public function payment_confirmation($amount,$transactionNo,$AppointmentNumber,$branch,$mobile)
    {
        //Appointment booking payment confirmation SMS
        //Your Payment Rs 1000/- Transaction No 1234 for Appointment Number AABBCC at Banjara Hills has been received. Team Rainbow
     $text = "Your Payment Rs ".$amount."/- Transaction No ".$transactionNo." for Appointment Number ".$AppointmentNumber." at ".$branch." has been received. Team Rainbow";
       return Sms::curlRequestSms($mobile,$text);

    }
    public function appointment_payment_failure($mobile)
    {
        //appointment booking not successful - payment issue
        $text = "Hi, Looks like there was an issue during the appointment booking process. If your payment wasn't successful, please try again. Else, wait for some time, or call 18002122. Team Rainbow";
        return Sms::curlRequestSms($mobile,$text);
    }
    public function new_patient_otp($mobile)
    {
        //New Patinet - OTP
        $text = "Dear {#var#{#var#}{#var#}}, Welcome to Rainbow Children's Hospital and Birthright. Your OTP for registering to our app is {#var#}. Team Rainbow";
        return Sms::curlRequestSms($mobile,$text);
    }
    public static function physical_consultation_reminder($mobile,$name,$doctor,$branch,$time)
    {
        //PC - Remainder
         $text = "Dear ".$name.", you have a consultation appointment with ".$doctor." in ".$branch." at ".$time." Please report 20 minutes prior to your appointment and also please cancel the appointment 2 hours prior if change in plans. Regards, Team Rainbow.";
        return Sms::curlRequestSms($mobile,$text);
    }
    public function patient_otp($mobile,$name,$otp)
    {
        //Old patinet login OTP
        //Dear {#var#}{#var#}, Welcome to Rainbow Children's Hospital and Birthright. Here is your OTP {#var#} -Team Rainbow
        $text = "Dear ".$name." , Welcome to Rainbow Children's Hospital and Birthright. Here is your OTP ".$otp." -Team Rainbow";
        return Sms::curlRequestSms($mobile,$text);
    }
    public function physical_consultation_confirmation($mobile,$name,$doctor,$branch,$date,$time,$link)
    {
        //PC - confirmation to patinet
        $text = "Dear ".$name.", your appointment is confirmed with  ".$doctor." at ".$branch." on ".$date." at ".$time.". Please reach the hospital ".$branch." before your appointment. Click here for your payment receipt ".$link.". For any query call 18002122 -Team Rainbow";

        return Sms::curlRequestSms($mobile,$text);
    }
    public function video_consultation_confirmation($mobile)
    {
        //VC - confirmation to patinet
        $text = "Dear {#var#}{#var#},Your VC has been confirmed with {#var#}{#var#} on {#var#} at {#var#}. Click here {#var#}{#var#} to join Video call with your doctor. Click here for your payment receipt {#var#}{#var#}.-Team Rainbow.";
        return Sms::curlRequestSms($mobile,$text);
    }
    public function physical_consultation_reschedule($mobile)
    {
        //PC - Reschedule
        $text = "Dear {#var#}{#var#} your appointment with {#var#}{#var#} at {#var#} has been rescheduled from {#var#}, {#var#} to {#var#}, {#var#}.-Team Rainbow";
        return Sms::curlRequestSms($mobile,$text);
    }
    public function video_consultation_reschedule($mobile)
    {
        //VC - Reschedule
        $text = "Dear {#var#}{#var#} your appointment with {#var#}{#var#} at {#var#} has been rescheduled from {#var#}, {#var#} to {#var#}, {#var#}.-Team Rainbow. Click here for your rescheduled VC link {#var#}, 
        Team Rainbow";
        return Sms::curlRequestSms($mobile,$text);
    }
}
