<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";
require_once "api/Crud/User/User.php";


class Promotion extends CrudClass implements CrudInterface
{
    protected  $name = "promotion";
    protected  $key = "idPromotion";
    protected  $attributes = [
        "idPromotion",
        "name",
        "dateBegin",
        "dateEnd",
        "idUser",
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO promotion(name, dateBegin, dateEnd, idUser) VALUES (?, ?, ?, ?)");
        $query->execute([
            $args["name"],
            $args["dateBegin"],
            $args["dateEnd"],
            $args["idUser"]
        ]);
    }
}
