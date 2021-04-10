<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";
require_once "api/Crud/ProductModel/ProductModel.php";
/*
class Offer extends CrudClass implements CrudInterface
{
    protected  $name = "offer";
    protected  $key = "idOffer";
    protected  $attributes = [
        "idOffer",
        "dateOffer",
        "price",
        "conditionOffer",
        "isAccepted",
        "idModel",
        "counterOffer"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO offer(dateOffer, price, conditionOffer, isAccepted, idModel, counterOffer) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([
            $args["dateOffer"],
            $args["price"],
            $args["conditionOffer"],
            $args["isAccepted"],
            $args["idModel"],
            $args["counterOffer"]
        ]);
    }
}*/
