<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";
require_once "api/Crud/Product/Product.php";
require_once "api/Crud/Order/Order.php";

class File extends CrudClass implements CrudInterface
{
    protected  $name = "file";
    protected  $key = "idFile";
    protected  $attributes = [
        "idFile",
        "filePath",
        "fileType",
        "idProduct",
        "idOrder"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO file(filePath ,fileType, idProduct, idOrder) VALUES (?, ?, ?, ?)");
        $query->execute([
            $args["filePath"],
            $args["fileType"],
            $args["idProduct"],
            $args["idOrder"]
        ]);
    }
}
