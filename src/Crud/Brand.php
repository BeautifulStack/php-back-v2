<?php

class Brand extends CrudClass implements CrudInterface
{
    public $name = "brand";
    protected $key = "idBrand";
    protected $attributes = [
        "idBrand",
        "brandName",
        "logo"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, $this->attributes, $this->key);

        $query = $this->conn->prepare("INSERT INTO brand(brandName, logo) VALUES (?, ?)");
        $query->execute([
            $args["brandName"],
            $args["logo"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
