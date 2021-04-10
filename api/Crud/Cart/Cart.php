<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Cart extends CrudClass implements CrudInterface
{
    protected $name = "cart";
    protected $key = "idCart";
    protected $attributes = [
        "idUser",
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO cart(idUser) VALUES (?)");
        $query->execute([
            $args["idUser"]
        ]);
    }
}
