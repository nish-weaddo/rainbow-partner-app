<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Whatsapp extends Eloquent
{

    public static function sendWhatsAppMsg($mobile,$parameters, $templatename)
    {
    
      

        $post_data = '{"recipient":{"id":"'.$mobile.'"},"kookoo_id":"OZNTLWA:917075988443", "type": "template","template": {"name": "'.$templatename.'","language": "en","parameters":'.$parameters.' }}';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://in1-ccaaspro.ozonetel.com/whatsApp_API/v1/WhatsAppSendOzone/reply');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: KKf1679f21481f0e821c01c0b4674e1df0',
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $response = curl_exec($ch);
        
        curl_close($ch);

        
        return $response;
    }

    public function appointment_cancellation($mobile,$name,$branch,$date,$time)
    {
       //vc_pc_cancellation
       $templatename ="vc_pc_cancellation";
        $parameters = '{"1":"'.$name.'","2":"'.$branch.'","3":"'.$date.' '.$time.'"}';

         return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
  
    public function payment_confirmation($amount,$transactionNo,$AppointmentNumber,$branch,$mobile)
    {
        
        $templatename ="appointment_booking_payment_confirmation_sms";
        $parameters = '{"1":"'.$amount.'/-","2":"'.$transactionNo.'","3":"'.$AppointmentNumber.'","4":"'.$branch.'"}';

         return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);

    }
    public function appointment_payment_failure($mobile)
    {
        $templatename ="appointment_booking_not_successful_payment_issue";
        $parameters = '{"1":"1800 2122"}';

         return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
  
    public function physical_consultation_confirmation($mobile,$name,$doctor,$branch,$date,$time,$link)
    {
        //pc_confirmation_to_patinet
        $templatename ="pc_confirmation_to_patinet";
        $parameters = '{"1":"'.$name.'","2":" ","3":"'.$doctor.'","4":" ","5":"'.$branch.'","6":" ","7":"'.$date.'","8":"'.$time.'","9":"20 minutes","10":"'.$link.'","11":"1800 2122"}';

        return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
    public function physical_consultation_confirmation_new($mobile,$name,$doctor,$branch,$date,$time,$link1,$link2)
    {
        //pc_confirmation_to_patinet
        $templatename ="pc_confirmation_patient_new";
        $parameters = '{"1":"'.$name.'","2":"'.$doctor.'","3":"'.$branch.'","4":"'.$date.'","5":"'.$time.'","6":"20 minutes","7":"'.$link1.'","8":"'.$link2.'","9":"1800 2122"}';

        return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
    public function video_consultation_confirmationn($mobile,$name,$doctor,$date,$time,$link,$videolink)
    {
        //VC - confirmation to patinet
        $templatename ="vc_confirmation_to_patinet";
        $parameters = '{"1":"'.$name.'","2":"'.$doctor.'","3":"'.$date.'","4":"'.$time.'","5":"'.$videolink.'","6":"'.$link.'"}';

        return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }


    public function physical_consultation_reschedule($mobile,$name,$doctor,$branch,$from_date_time,$to_date_time)
    {
        //PC - Reschedule
        $templatename ="pc_reschedule_1";
        $parameters = '{"1":"'.$name.'","2":"'.$doctor.'","3":"'.$branch.'","4":"'.$from_date_time.'","5":"'.$to_date_time.'"}';

         return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
    public function video_consultation_reschedule($mobile,$name,$doctor,$from_date,$from_time,$to_date,$to_time,$videolink)
    {
        //VC - Reschedule
        $templatename ="vc_reshedule";
        $parameters = '{"1":"'.$name.'","2":"'.$doctor.'","3":"'.$from_date.'","4":"'.$from_time.'","5":"'.$to_date.'","6":"'.$to_time.'","7":"'.$videolink.'"}';

         return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
    public static function physical_consultation_reminder($mobile,$name,$doctor,$branch,$time)
    {
        //pc_emainder
        /*Dear {{1}}, you have a consultation appointment with {{2}} in {{3}} at {{4}}
Please report 20 minutes prior to your appointment and also please cancel the appointment 2 hours prior if change in plans. */

        $templatename ="pc_emainder";
        $parameters = '{"1":"'.$name.'","2":"'.$doctor.'","3":"'.$branch.'","4":"'.$time.'"}';

         return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
    public static function video_consultation_reminder($mobile,$time)
    {
        //vc_remainder

        $templatename ="vc_remainder";
        $parameters = '{"1":"'.$time.'"}';

         return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
    public static function video_consultation_confirmation_doctor($mobile,$patientname,$doctor,$date,$time,$videolink)
    {
        //VC - confirmation to patinet
        $templatename ="vc_confirmation_doctor";
        $parameters = '{"1":"'.$doctor.'","2":"'.$patientname.'","3":"'.$date.'","4":"'.$time.'","5":"'.$videolink.'"}';

        return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
    public static function video_consultation_reschedule_doctor($mobile,$patientname,$doctor,$from_date,$from_time,$to_date,$to_time,$videolink)
    {
        //VC - Reschedule
        $templatename ="vc_reshedule_doctor";
        $parameters = '{"1":"'.$doctor.'","2":"'.$patientname.'","3":"'.$from_date.'","4":"'.$from_time.'","5":"'.$to_date.'","6":"'.$to_time.'","7":"'.$videolink.'"}';

         return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }

    public static function pay_at_hospital($mobile,$patientname,$doctor,$branch,$date,$time,$minute,$call_number)
    {
        /*

         *
         *Dear Shravya, your appointment is confirmed with Dr. Swara at Banjara Hills on 10-04-2024 at 5:00 PM. Please reach the hospital 20 Min before your appointment. For any query call Call Center Number -Team Rainbow
         */
        $templatename ="appointment_booking_for_pay_at_hospital";
        $parameters = '{"1":"'.$patientname.'","2":"'.$doctor.'","3":"'.$branch.'","4":"'.$date.'","5":"'.$time.'","6":"'.$minute.'","7":"'.$call_number.'"}';

        return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }

    public static function payment_link_sent($mobile,$name,$link)
    {
        //rch_vc_discussions

        $templatename ="rch_vc_discussions";
        $parameters = '{"1":"'.$name.'","2":"'.$link.'"}';
        return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }

    public static function payment_but_slot_fail($mobile,$name)
    {
        //rch_vc_discussions

        $templatename ="slot_not_booked";
        $parameters = '{"1":"'.$name.'"}';
        return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
    public static function vaccination_reminder($mobile,$duedate)
    {
        $templatename ="vaccination_it2";
        $parameters = '{"1":"'.$duedate.'"}';
        return Whatsapp::sendWhatsAppMsg($mobile,$parameters, $templatename);
    }
}
