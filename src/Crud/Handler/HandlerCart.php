<?php

class HandlerCart extends Handler
{
    protected function readAll(array $pathArr): array
    {
        if (isset($_SESSION["id"])) {
            $result = $this->object->where(["idUser" => $_SESSION["id"]]);
            return $result;
        } else {
            echo json_encode(array("errors" => [
                "Please Login Before"
            ]));
            exit();
        }
    }

    public function route(array $pathArr): array
    {
        if ($pathArr[1] === "Content") return $this->getCart($pathArr);
        return parent::route($pathArr);
    }

    protected function getCart(): array

    {
        // Ici j'aurai bien utiliser la DB de cart à la place mais elle est en protected
        $db = new Database();
        $object = new Product($db->conn);
        $objectCart = new Cart($db->conn);
        $handlerCart = new HandlerCart($objectCart);
        $res = $handlerCart->object->where(["idUser" => $_SESSION["id"]]);
        $res = end($res);
        $cartId = $res["idCart"];


        $handler = new HandlerProduct($object);
        $results = $handler->object->where(["idCart" => $cartId]);

        return array("content" => $results);
    }
}
