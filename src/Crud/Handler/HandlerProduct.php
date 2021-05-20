<?php

class HandlerProduct extends Handler
{

    public function route(array $pathArr): array
    {
        if ($pathArr[1] === "AddToCart") return $this->addToCart($pathArr);
        if ($pathArr[1] === "RemoveFromCart") return $this->removeFromCart($pathArr);
        return parent::route($pathArr);
    }


    protected function addToCart(): array
    {
        $db = new Database();
        $object = new Cart($db->conn);
        $handler = new HandlerCart($object);

        $result = $handler->object->where(["idUser" => $_SESSION["id"]]);

        if (count($result) === 0) {
            $cart = $handler->object->create();
            $cart["idCart"] = $cart["id"];
        } else {
            $cart = end($result);
        }

        $this->object->update(["id" => $_POST["id"], "idCart" => $cart["idCart"]]);

        return array("id" =>  $_POST["id"]);
    }

    protected function removeFromCart(): array
    {
        $this->object->update(["id" => $_POST["id"], "idCart" => null]);

        return array("id" =>  $_POST["id"]);
    }

    protected function readAll(array $pathArr): array
    {

        $result = $this->object->conn->query("SELECT
                                                    product.idProduct as idProduct,
                                                    product_model.modelName as modelName,
                                                    product_model.officialPrice as officialPrice,
                                                    product_model.idModel as idModel,
                                                    brand.brandName as brandName,
                                                    brand.idBrand as idBrand,
                                                    category.idCategory as idCategory,
                                                    category.categoryName as categoryName,
                                                    image.path as path
                                                FROM product
                                                    INNER JOIN product_model ON product.idModel = product_model.idModel
                                                    INNER JOIN brand on product_model.idBrand = brand.idBrand
                                                    INNER JOIN category ON product_model.idCategory = category.idCategory
                                                    INNER JOIN image ON product_model.idModel = image.idModel
                                                WHERE
                                                    disponibility = 1;");
        $TrueResult = $result->fetchAll(PDO::FETCH_ASSOC);
        return $TrueResult;
    }

    protected function read(array $pathArr)
    {
        $result = parent::read($pathArr);

        return $result;
    }
}
