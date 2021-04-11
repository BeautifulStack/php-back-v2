<?php

class ProductModel extends CrudClass implements CrudInterface
{
    protected $name = "product_model";
    protected $key = "idModel";
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
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO product_model(modelName, officialPrice, idBrand, idCategory) VALUES (?, ?, ?, ?); SELECT LAST_INSERT_ID() as id;");
        $query->execute([
            $args["modelName"],
            $args["officialPrice"],
            $args["idBrand"],
            $args["idCategory"]
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}