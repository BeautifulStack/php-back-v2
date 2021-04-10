<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";
require_once "api/Crud/Association/Association.php";

class Project extends CrudClass implements CrudInterface
{
    protected $name = "project";
    protected $key = "idProject";
    protected $attributes = [
        "idProject",
        "name",
        "description",
        "idAssociation"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO project(name, description, idAssociation) VALUES (?, ?, ?)");
        $query->execute([
            $args["name"],
            $args["description"],
            $args["idAssociation"]
        ]);
    }
}
