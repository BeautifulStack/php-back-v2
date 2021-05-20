<?php

class Stripe
{
    public static function do_payment($args)
    {
        if (!isset($args[1]) || !isset($_POST["payment_method"])) {
            echo json_encode(
                array("errors" => [
                    "Missing payment argument !"
                ])
            );
            http_response_code(400);
            exit;
        }

        switch ($args[1]) {
            case "Create":

                $db = new Database();
                $cart = new Cart($db->conn);
                $handler = new HandlerCart($cart);
                $amount = $handler->route(["Cart", "Price"]);
                $cart_id = $handler->cart_by_user();

                $payment = Stripe::createPayment($_POST["payment_method"], $amount["price"]);

                if (isset($payment["status"])) {

                    $order = new Order($db->conn);
                    $order->create([
                        "totalPrice" => $amount["price"],
                        "addressDest" => $_POST["delivery_address"],
                        "deliveryMode" => "Chronopost",
                        "deliveryStatus" => "In Warehouse",
                        "isPaid" => 1,
                        "idCart" => $cart_id,
                        "orderDate" => "tkt mon reuf"
                    ]);

                    $content = $handler->route(["Cart", "Content"]);
                    $p = new Product($db->conn);
                    foreach ($content["content"] as $product){
                        $p->update(["id" => $product["idProduct"], "disponibility" => 0]);
                    }

                    $cart->create(["idUser" => $_SESSION["id"]]);

                    return ["status" => $payment["status"]];
                }
                else return $payment["error"];

            default :
                echo json_encode(
                    array("errors" => [
                        "Missing payment argument !"
                    ])
                );
                http_response_code(400);
                exit();
        }
    }

    public static function createPayment($payment_method, $amount)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.stripe.com/v1/payment_intents',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'amount=' . $amount . '&currency=eur&confirm=true&payment_method=' . $payment_method,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . STRIPE_API_TOKEN,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, TRUE);

        return $response ?? 0;
    }
}
