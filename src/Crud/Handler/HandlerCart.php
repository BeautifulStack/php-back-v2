<?php

class HandlerCart extends Handler
{
    protected function readAll(array $pathArr): array
    {
        if (isset($_SESSION["id"])) {
            return $this->object->where(["idUser" => $_SESSION["id"]]);
        } else {
            echo json_encode(array("errors" => [
                "Please Login Before"
            ]));
            exit();
        }
    }

    public function route(array $pathArr): array
    {
        if ($pathArr[1] === "Content") return $this->getCart();
        if ($pathArr[1] === "Price") return $this->getPrice();

        return parent::route($pathArr);
    }

    protected function getProducts(): array
    {

        $db = new Database();
        $product = new Product($db->conn);
        $cart = new Cart($db->conn);
        $res = $cart->where(["idUser" => $_SESSION["id"]]);
        $res = end($res);
        $cartId = $res["idCart"];

        return $product->where(["idCart" => $cartId]);
    }

    protected function getCart(): array

    {
        return array("content" => $this->getProducts());
    }

    protected function getPrice(): array
    {
        $db = new Database();
        $objectCart = new Cart($db->conn);
        $handlerCart = new HandlerCart($objectCart);
        $res = $handlerCart->object->where(["idUser" => $_SESSION["id"]]);
        $res = end($res);
        $cartId = $res["idCart"];

        $query = "SELECT * FROM product INNER JOIN product_model ON product.idModel = product_model.idModel WHERE idCart = ?";
        $query = $db->conn->prepare($query);
        $query->execute([$cartId]);

        $res = $query->fetchAll();
        $total = 0;
        foreach ($res as $row) {
            $total += $row["officialPrice"] * 0.66;
        }

        $total = round($total);
        return array("price" => $total);
    }
}
