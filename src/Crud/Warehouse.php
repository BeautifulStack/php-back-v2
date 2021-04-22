<?php

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
        $args = $this->check_attributes_create($args, $this->attributes, $this->key);

        $query = $this->conn->prepare("INSERT INTO warehouse(location, maxCapacity) VALUES (?, ?)");
        $query->execute([
            $args["location"],
            $args["maxCapacity"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
