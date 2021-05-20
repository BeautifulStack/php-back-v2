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
        $cartId = $this->cart_by_user();

        return $product->where(["idCart" => $cartId]);
    }

    protected function getCart(): array

    {
        return array("content" => $this->getProducts());
    }

    protected function getPrice(): array
    {
        $cartId = $this->cart_by_user();

        $query = "SELECT * FROM product INNER JOIN product_model ON product.idModel = product_model.idModel WHERE idCart = ?";
        $query = $this->object->conn->prepare($query);
        $query->execute([$cartId]);

        $res = $query->fetchAll();
        $total = 0;
        foreach ($res as $row) {
            $total += $row["officialPrice"] * 0.66;
        }

        $total = round($total);
        return array("price" => $total);
    }

    public function cart_by_user()
    {
        $q = $this->object->conn->prepare("SELECT MAX(idCart) as id FROM cart WHERE idUser = ?");
        $q->execute([$_SESSION["id"]]);
        $res = $q->fetchAll(PDO::FETCH_ASSOC);
        return $res[0]["id"];
    }
}
