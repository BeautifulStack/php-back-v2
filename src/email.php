<?php

class Email {


    public static function send_email($email, $code, $name, $firstName) {



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.mailjet.com/v3.1/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
        "Messages":[
                {
                        "From": {
                                "Email": "lealaroze6@gmail.com",
                                "Name": "Mailjet Pilot"
                        },
                        "To": [
                                {
                                        "Email": "'.$email.'"
                                }
                        ],
                        "Subject": "VALIDERRRR!",
                        "TextPart": "Dear passenger 1, welcome to Mailjet! May the delivery force be with you!",
                        "HTMLPart": "<h3>Dear passenger 1, welcome to <a href=\"http://localhost/php-back/verification?id='.$code.'\">Mailjet</a>!</h3><br />May the delivery force be with you!"
                }
        ]
    }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic NzBjOWExMTM3ZGVkYTQ2MDY2NDQ2ZjY0MDc5OWM3M2I6Yg==',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
    }
}

