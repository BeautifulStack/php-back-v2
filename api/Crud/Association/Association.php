<?php

class Association extends CrudClass implements CrudInterface
{
    protected $name = "association";
    protected $key = "idAssociation";
    protected $attributes = [
        "idAssociation",
        "name",
        "description",
        "logo"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO association(name, description, logo) VALUES (?, ?, ?); SELECT LAST_INSERT_ID() as id;");
        $query->execute([
            $args["name"],
            $args["description"],
            $args["logo"]
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
