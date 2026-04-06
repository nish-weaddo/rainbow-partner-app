<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    
    public function curlRequest($postData, $api)
    {
        $curl = curl_init();
        $his_url = config('services.his_api_base_url');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'http://43.205.172.7:5005/thirdparty/' . $api,
            CURLOPT_URL => $his_url. $api,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //return $response;
    }
    public static function curlAuthRequest($postData, $api)
    {
        $curl = curl_init();
        $his_url = config('services.his_api_base_url');



        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://10.10.225.15:5005/thirdparty/' . $api,
            // CURLOPT_URL => $his_url. $api,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // if($api=="ManagePatientAppointment")
        // {
        //     $myfile = fopen("/var/www/html/rainbowhospitals.in/public/his_log/".date('dmY')."_his_apt_data.txt", "a+") or die("Unable to open file!");
        //     fwrite($myfile, PHP_EOL.'Request Data: '.$postData .' >> Response Data: '.$response.PHP_EOL);
        //     fclose($myfile);
        // }

        // if ($api == "ManagePatientAppointment") {  // PREVIOUS WORKING
        if (($api == "ManagePatientAppointment") || ($api == "PatientAdvancePay")) { //THIS IS FOR LOG PatientAdvancePay ALSO

            // ADDED FOR CHECK THE URL START
            
            $postDataArr = json_decode($postData, true);

            // Add new key
            $postDataArr['request_url'] = $his_url . $api;

            // Re-encode for logging
            $jsonPostData = json_encode($postDataArr);
            
            // ADDED FOR CHECK THE URL END

            $logPath = public_path('his_log/' . date('dmY') . '_his_apt_data.txt');

            $myfile = fopen($logPath, "a+") or die("Unable to open file!");
            fwrite($myfile, PHP_EOL . 'Request Data: ' . $jsonPostData . ' >> Response Data: ' . $response . PHP_EOL);
            // fwrite($myfile, PHP_EOL . 'Request Data: ' . $postData . ' >> Response Data: ' . $response . PHP_EOL);
            fclose($myfile);
        }

       

        return $response;
    }

    public static function curlAuthRequest_test($postData, $api)
    {
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://43.205.172.7:5005/thirdparty/' . $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        echo "<pre>";
        print_r($response);
        die;
        curl_close($curl);

        if($api=="ManagePatientAppointment")
        {
            $myfile = fopen("/var/www/html/rainbowhospitals.in/public/his_log/".date('dmY')."_his_apt_data.txt", "a+") or die("Unable to open file!");
            fwrite($myfile, PHP_EOL.'Request Data: '.$postData .' >> Response Data: '.$response.PHP_EOL);
            fclose($myfile);
        }
       

        return $response;
    }

    public function curlAuthRequest_UAT($postData, $api)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://65.0.243.65:5005/thirdparty/' . $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }


    //test function


    public function test_id($id)
    {
        return $id;
    }

    /* FUNCTION FOR GENERATE VIDEO LINK START */
    public function generateVideoLink($postData){
        $bearerToken = "1AFE9DA1661389E5624C99C8EDB3D8EB6E4905066C573B8FB7203F3EF1384880";

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://rainbow.exwyn.com/api/his/generateMeetingLink',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$bearerToken
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));
        $response = curl_exec($curl);

        $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        if(curl_errno($curl)){
            echo 'cURL Error: ' . curl_error($curl);
        }
        
        curl_close($curl);
        
        return $response;
    }
    /* FUNCTION FOR GENERATE VIDEO LINK END */
    
}
