<?php

class CaractValue extends CrudClass implements CrudInterface
{
    protected $name = "caract_value";
    protected $key = "idCaract";
    protected $attributes = [
        "idCaract",
        "caractName",
        "caractValue",
        "idModel"
    ];
    protected $foreignKey = [
        "idModel" => ["product_model", "modelName"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, $this->attributes, $this->key);

        $query = $this->conn->prepare("INSERT INTO caract_value(caractName, caractValue, idModel) VALUES (?, ?, ?)");
        $query->execute([
            $args["caractName"],
            $args["caractValue"],
            $args["idModel"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function read_by_id($id): array
    {
        $query = $this->conn->prepare("SELECT caractName,caractValue FROM caract_value WHERE idModel = ?");
        $query->execute([$id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read_id_by_name($id, $name): array
    {
        $query = $this->conn->prepare("SELECT idCaract FROM caract_value WHERE idModel = ? AND caractName = ?");
        $query->execute([$id, $name]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
