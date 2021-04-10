<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";
require_once "api/Crud/User/User.php";
require_once "api/Crud/Offer/Offer.php";

class UserOffer extends CrudClass implements CrudInterface
{
    protected  $name = "user_offer";
    protected  $key = "idOffer";
    protected  $attributes = [
        "idUser",
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO user_offer(idOffer, idUser) VALUES (?, ?)");
        $query->execute([
            $args["idOffer"],
            $args["idUser"],
        ]);
    }
}
