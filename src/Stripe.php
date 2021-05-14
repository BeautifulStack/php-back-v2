<?php

class Stripe
{
    private $payementMethodId;
    private $payementIntent;
    private $payementAmount;
    private $payementStatus;

    public function __construct(int $amount = 0, string $payementIntent = '')
    {
        $this->payementAmount = $amount;
        $this->payementIntent = $payementIntent;
    }

    public function pay()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.stripe.com/v1/payment_intents/' . $this->payementIntent . '/confirm',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'payment_method=' . $this->payementMethodId,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . STRIPE_API_TOKEN,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, TRUE);

        $payementStatus = isset($response['status']) ? $response['status'] : 0;
        $this->payementStatus = $payementStatus;
        return isset($payementStatus) ? $payementStatus : 0;
    }

    public function createPayementIntent()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.stripe.com/v1/payment_intents',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'amount=' . $this->payementAmount . '&currency=eur&payment_method_types%5B%5D=card',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . STRIPE_API_TOKEN,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, TRUE);

        $payementId = isset($response['id']) ? $response['id'] : 0;
        $this->payementIntent = $payementId;
        return $payementId !== 0;
    }

    public function registerCard(array $cardInfo)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.stripe.com/v1/payment_methods',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'type=card&card%5Bnumber%5D=' . $cardInfo['number'] . '&card%5Bexp_month%5D=' . $cardInfo['exp_month'] . '&card%5Bexp_year%5D=' . $cardInfo['exp_year'] . '&card%5Bcvc%5D=' . $cardInfo['cvc'],
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . STRIPE_API_TOKEN,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, TRUE);

        $payementMethodId = isset($response['id']) ? $response['id'] : 0;
        $this->payementMethodId = $payementMethodId;
        return $payementMethodId !== 0;
    }

    public function updateStatus()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.stripe.com/v1/payment_intents/' . $this->payementIntent,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . STRIPE_API_TOKEN,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, TRUE);

        $payementStatus = isset($response['status']) ? $response['status'] : 0;
        $this->payementStatus = $payementStatus;
        return isset($payementStatus) ? $payementStatus : 0;
    }

    public function getStatus()
    {
        return $this->payementStatus;
    }
}
