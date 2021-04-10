<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Category extends CrudClass implements CrudInterface
{
    protected $name = "category";
    protected $key = "idCategory";
    protected $attributes = [
        "idCategory",
        "categoryName"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO category(categoryName) VALUES (?)");
        $query->execute([
            $args["categoryName"]
        ]);
    }
}
