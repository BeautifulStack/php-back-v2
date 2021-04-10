<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Transfer extends CrudClass implements CrudInterface
{
    protected  $name = "transfer";
    protected  $key = "idTransfer";
    protected  $attributes = [
        "amount",
        "transferDate",
        "idUser",
        "idProject"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO transfer(amount, transferDate, idUser, idProject) VALUES (?, ?, ?, ?)");
        $query->execute([
            $args["amount"],
            $args["transferDate"],
            $args["idUser"],
            $args["idProject"],
        ]);
    }
}
