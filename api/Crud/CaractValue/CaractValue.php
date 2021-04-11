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
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO caract_value(caractName, caractValue, idModel) VALUES (?, ?, ?); SELECT LAST_INSERT_ID() as id;");
        $query->execute([
            $args["caractName"],
            $args["caractValue"],
            $args["idModel"]
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
