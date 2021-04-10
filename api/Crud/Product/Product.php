<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";
require_once "api/Crud/ProductModel/ProductModel.php";
require_once "api/Crud/Warehouse/Warehouse.php";
require_once "api/Crud/Offer/Offer.php";
require_once "api/Crud/Cart/Cart.php";

class Product extends CrudClass implements CrudInterface
{
    protected $name = "product";
    protected $key = "idProduct";
    protected $attributes = [
        "idProduct",
        "idModel",
        "idWarehouse",
        "idOffer",
        "idCart"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO product(idProduct, idModel, idWarehouse, idOffer, idCart) VALUES (?, ?, ?, ?, ?)");
        $query->execute([
            $args["idProduct"],
            $args["idModel"],
            $args["idWarehouse"],
            $args["idOffer"],
            $args["idCart"]
        ]);
    }
}
