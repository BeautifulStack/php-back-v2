<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";
require_once "api/Crud/Brand/Brand.php";
require_once "api/Crud/Category/Category.php";
/*
class Product_model extends CrudClass implements CrudInterface
{
    protected  $name = "product_model";
    protected  $key = "idModel";
    protected  $attributes = [
        "idModel",
        "modelName",
        "officialPrice",
        "idBrand",
        "idCategory"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO product_model(modelName, officialPrice, idBrand, idCategory) VALUES (?, ?, ?, ?)");
        $query->execute([
            $args["modelName"],
            $args["officialPrice"],
            $args["idBrand"],
            $args["idCategory"]
        ]);
    }
}
*/