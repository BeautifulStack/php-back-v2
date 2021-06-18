<?php

/**
 * Class BlockchainClient
 *
 * The API class to interact with the blockchain master node
 *
 * Any methods doesn't check variables that are passed through
 */
class BlockchainClient
{
    private $private_key;
    //private $public_key;

    /**
     * Constructor used to load private key
     */
    public function __construct()
    {
        // Check if private key already exist, if not create one
        if (!file_exists("data/private.key")) {
            $this->generate_keys();
        }

        // Fetch private key from file and ready it
        $this->private_key = openssl_pkey_get_private("file://data/private.key");
    }

    /**
     * Sign data string with the current private key
     *
     * @param $data string Data to be sign
     * @return string Return data signature in base64 encoding
     */
    private function sign(string $data): string
    {
        // Create signature
        openssl_sign($data, $signature, $this->private_key, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }

    /**
     * Generate new pair of keys and save them in files
     */
    private function generate_keys()
    {
        $new_key_pair = openssl_pkey_new(array(
            "private_key_bits" => 1024,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ));

        // Get private key string
        openssl_pkey_export($new_key_pair, $private_key);

        // Get public key
        $details = openssl_pkey_get_details($new_key_pair);
        $public_key = $details['key'];

        // Save them
        file_put_contents("data/private.key", $private_key);
        file_put_contents("data/public.key", $public_key);
    }

    /**
     * Send request to blockchain node
     *
     * @param $target string Select the method to send (balance, new_reward, etc...)
     * @param $method string Select method, GET or POST
     * @param mixed $body If method is GET than leave this empty, contain json body request
     * @return mixed Return json response deserialized
     */
    private function send_request(string $target, string $method, $body = null)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://blockchain.octobyte.cloud/$target",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true) ?? $response;
    }

    /**
     * Get the balance of a project by it address
     *
     * @param string $address Address you want the balance
     * @return int Return balance integer from response, return at least 0 anyway
     */
    public function get_balance(string $address): int
    {
        $res = $this->send_request("balance/$address", "GET");
        return $res["balance"];
    }

    /**
     * Blockchain request to add a reward to a buyer, build the transaction, serialize it and sign it
     *
     * @param int $amount Select the amount to reward, calculated elsewhere
     * @param string $address Select the address from the user profile
     * @return bool Return if request is successful
     */
    public function new_reward(int $amount, string $address): bool
    {
        $date = new DateTime();

        $transaction = [
            "amount" => $amount,
            "receiver" => $address,
            "sender" => "REWARD",
            "time" => $date->getTimestamp()
        ];

        $body = [
            "transaction" => $transaction,
            "signature" => $this->sign(json_encode($transaction))
        ];

        $res = $this->send_request("new_reward", "POST", json_encode($body));

        return $res == "Ok";
    }

    /**
     * To withdraw the money from a project, on the blockchain side you need to build a special transaction indicating
     * in the blockchain history that it has been retrieved
     *
     * @param string $address The address of the project to withdraw
     * @return bool Return if request is successful
     */
    public function new_withdraw(string $address): bool
    {
        $date = new DateTime();
        $amount = $this->get_balance($address);

        $transaction = [
            "amount" => $amount,
            "receiver" => "WITHDRAW",
            "sender" => $address,
            "time" => $date->getTimestamp()
        ];

        $body = [
            "transaction" => $transaction,
            "signature" => $this->sign(json_encode($transaction))
        ];

        $res = $this->send_request("new_reward", "POST", json_encode($body));

        return $res == "Ok";
    }

    /**
     * When adding a warehouse, if it has a running node then use this function to add it to master node's peers
     *
     * @param string $ip_address Select the ip address of the warehouse node
     * @return bool Return if request is successful
     */
    public function new_peer(string $ip_address): bool
    {
        $body = [
            "peer" => $ip_address,
            "signature" => $this->sign($ip_address)
        ];

        $res = $this->send_request("new_peer", "POST", json_encode($body));

        return $res == "Ok";
    }
}
