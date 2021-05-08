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
        $cart = end($result);

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
        $result = parent::readAll($pathArr);
        $result = $this->object->matchModelBrand($result);

        return $result;
    }

    protected function read(array $pathArr)
    {
        $result = parent::read($pathArr);

        return $result;
    }
}
