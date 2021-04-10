<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Brand extends CrudClass implements CrudInterface
{
    protected $name = "brand";
    protected $key = "idBrand";
    protected $attributes = [
        "idBrand",
        "brandName"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO brand(brandName) VALUES (?)");
        $query->execute([
            $args["brandName"]
        ]);
    }
}
