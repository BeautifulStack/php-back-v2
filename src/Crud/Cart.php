<?php

class Cart extends CrudClass implements CrudInterface
{
    protected $name = "cart";
    protected $key = "idCart";
    protected $attributes = [
        "idCart",
        "idUser"
    ];
    protected $foreignKey = [
        "idUser" => ["user", "lastName"]
    ];

    public function create(array $args = [])
    {
        // $args = $this->check_attributes_create($args, $this->attributes, $this->key, ["idUser"]);

        if (!isset($_SESSION["id"])) {
            echo json_encode(array("errors" => "You must be logged to create a cart"));
            exit();
        }

        $args = ["idUser" => $_SESSION["id"]];

        $query = $this->conn->prepare("INSERT INTO cart(idUser) VALUES (?)");
        $query->execute([
            $args["idUser"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
