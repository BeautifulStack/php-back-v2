<?php

class Email
{


    public static function send_email($email, $code)
    {
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
            CURLOPT_POSTFIELDS => '{
        "Messages":[
                {
                        "From": {
                                "Email": "lealaroze6@gmail.com",
                                "Name": "FairRepack Team"
                        },
                        "To": [
                                {
                                        "Email": "' . $email . '"
                                }
                        ],
                        "Subject": "Please validate your account !",
                        "TextPart": "You\'re almost ready to get started !",
                        "HTMLPart": "Hey !<br> Click on the link bellow to verify your email address and complete your FairRepack account setup <a href=\"http://localhost/php-back/verification?id=' . $code . '\">Verify your Account</a> !<br />Verifying your email ensures that you can access and manage your account.<br>If you did not create a FairRepack account, no further action is needed.<br>Cheers,<br>FairRepack Team"
                }
        ]
    }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic NzBjOWExMTM3ZGVkYTQ2MDY2NDQ2ZjY0MDc5OWM3M2I6NDhmMDRhY2I3ODkxYTQzNmQ3ZDM5NWFlYjhkZmMwMWM=',
                'Content-Type: application/json'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        //echo $response;
        //var_dump($response);
    }
}
