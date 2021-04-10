<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Warehouse extends CrudClass implements CrudInterface
{
    protected $name = "warehouse";
    protected $key = "idWarehouse";
    protected $attributes = [
        "idWarehouse",
        "location",
        "maxCapacity",
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO warehouse(location, maxCapacity) VALUES (?, ?)");
        $query->execute([
            $args["location"],
            $args["maxCapacity"]
        ]);
    }
}