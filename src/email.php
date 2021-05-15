<?php

class Email {


    public static function send_email($email, $code) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.mailjet.com/v3.1/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
        "Messages":[
                {
                        "From": {
                                "Email": "lealaroze6@gmail.com",
                                "Name": "Lea"
                        },
                        "To": [
                                {
                                        "Email": "antoine.lorin2@gmail.com"
                                }
                        ],
                        "Subject": "Your email flight plan!",
                        "TextPart": "Dear passenger 1, welcome to Mailjet! May the delivery force be with you!",
                        "HTMLPart": "<h3>Dear passenger 1, welcome to <a href=\\"https://www.mailjet.com/\\">Mailjet</a>!</h3><br />May the delivery force be with you!"
                }
        ]
    }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic NzBjOWExMTM3ZGVkYTQ2MDY2NDQ2ZjY0MDc5OWM3M2I6NDhmMDRhY2I3ODkxYTQzNmQ3ZDM5NWFlYjhkZmMwMWM=',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;


//        $curl = curl_init();
//        $params = array(
//            CURLOPT_URL => 'https://api.mailjet.com/v3.1/send',
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'POST',
//            CURLOPT_POSTFIELDS => '{
//        "Messages":[
//                {
//                        "From": {
//                                "Email": "lealaroze6@gmail.com",
//                                "Name": "Mailjet Pilot"
//                        },
//                        "To": [
//                                {
//                                        "Email": "'.$email.'"
//                                }
//                        ],
//                        "Subject": "VALIDERRRR!",
//                        "TextPart": "Dear passenger 1, welcome to Mailjet! May the delivery force be with you!",
//                        "HTMLPart": "<h3>Dear passenger 1, welcome to <a href=\"http://localhost/php-back/verification?id='.$code.'\">Mailjet</a>!</h3><br />May the delivery force be with you!"
//                }
//        ]
//    }',
//            CURLOPT_HTTPHEADER => array(
//                'Authorization: Basic NzBjOWExMTM3ZGVkYTQ2MDY2NDQ2ZjY0MDc5OWM3M2I6Yg==',
//                'Content-Type: application/json'
//            ),
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//        );
//        curl_setopt_array($curl, $params);
//
//        $mes = ($params[CURLOPT_POSTFIELDS]);
//
//        $response = curl_exec($curl);
//        curl_close($curl);
//        var_dump($mes);
        var_dump($response);
    }
}

