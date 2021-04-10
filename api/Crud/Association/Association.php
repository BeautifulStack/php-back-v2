<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Association extends CrudClass implements CrudInterface
{
    protected $name = "association";
    protected $key = "idAssociation";
    protected $attributes = [
        "idAssociation",
        "name",
        "description"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO association(name, description) VALUES (?, ?)");
        $query->execute([
            $args["name"],
            $args["description"]
        ]);
    }
}
