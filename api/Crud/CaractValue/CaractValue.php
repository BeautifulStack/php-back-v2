<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";
require_once "api/Crud/ProductModel/ProductModel.php";

class CaractValue extends CrudClass implements CrudInterface
{
    protected  $name = "caract_value";
    protected  $key = "idCaract";
    protected  $attributes = [
        "idCaract",
        "caractName",
        "caractValue",
        "idModel"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO caract_value(caractName, caractValue, idModel) VALUES (?, ?, ?)");
        $query->execute([
            $args["caractName"],
            $args["caractValue"],
            $args["idModel"]
        ]);
    }
}
