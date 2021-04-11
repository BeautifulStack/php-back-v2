<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Offer extends CrudClass implements CrudInterface
{
    protected $name = "offer";
    protected $key = "idOffer";
    protected $attributes = [
        "idOffer",
        "dateOffer",
        "price",
        "conditionOffer",
        "isAccepted",
        "idModel",
        "counterOffer",
        "idUser"
    ];
    protected $foreignKey = [
        "idModel" => ["product_model", "modelName"],
        //"counterOffer" => ["offer", "idOffer"],
        "idUser"  => ["user", "lastName"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO offer(price, conditionOffer, isAccepted, idModel, counterOffer, idUser) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([
            $args["price"],
            $args["conditionOffer"],
            $args["isAccepted"],
            $args["idModel"],
            $args["counterOffer"],
            $args["idUser"]
        ]);
    }
}