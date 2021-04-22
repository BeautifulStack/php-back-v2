<?php

class ProductModel extends CrudClass implements CrudInterface
{
    public $name = "product_model";
    public $key = "idModel";
    protected $attributes = [
        "idModel",
        "modelName",
        "officialPrice",
        "idBrand",
        "idCategory"
    ];
    protected $foreignKey = [
        "idBrand" => ["brand", "brandName"],
        "idCategory" => ["category", "categoryName"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, $this->attributes, $this->key);

        $query = $this->conn->prepare("INSERT INTO product_model(modelName, officialPrice, idBrand, idCategory) VALUES (?, ?, ?, ?)");
        $query->execute([
            $args["modelName"],
            $args["officialPrice"],
            $args["idBrand"],
            $args["idCategory"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}