<?php

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
    protected $foreignKey = [
        "idAssociation" => ["association", "name"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, $this->attributes, $this->key);

        $query = $this->conn->prepare("INSERT INTO project(name, description, idAssociation) VALUES (?, ?, ?)");
        $query->execute([
            $args["name"],
            $args["description"],
            $args["idAssociation"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
