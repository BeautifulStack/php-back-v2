<?php

class Association extends CrudClass implements CrudInterface
{
    public $name = "association";
    protected $key = "idAssociation";
    protected $attributes = [
        "idAssociation",
        "name",
        "description",
        "logo"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, $this->attributes, $this->key);

        $query = $this->conn->prepare("INSERT INTO association(name, description, logo) VALUES (?, ?, ?)");
        $query->execute([
            $args["name"],
            $args["description"],
            $args["logo"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
