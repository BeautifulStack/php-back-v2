<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Product extends CrudClass implements CrudInterface
{
    protected $name = "product";
    protected $key = "idProduct";
    protected $attributes = [
        "idProduct",
        "disponibility",
        "conditionProduct",
        "date",
        "isDelivered",
        "idModel",
        "idWarehouse",
        "idOffer",
        "idCart"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO product(disponibility, conditionProduct, isDelivered, idModel, idWarehouse, idOffer, idCart) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->execute([
            $args["disponibility"],
            $args["conditionProduct"],
            $args["isDelivered"],
            $args["idModel"],
            $args["idWarehouse"],
            $args["idOffer"],
            $args["idCart"]
        ]);
    }
}
