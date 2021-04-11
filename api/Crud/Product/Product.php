<?php

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
    protected $foreignKey = [
        "idModel" => ["product_model", "modelName"],
        "idWarehouse" => ["warehouse", "location"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO product(disponibility, conditionProduct, isDelivered, idModel, idWarehouse, idOffer, idCart) VALUES (?, ?, ?, ?, ?, ?, ?); SELECT LAST_INSERT_ID() as id;");
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
