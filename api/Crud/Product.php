<?php

class Product extends CrudClass implements CrudInterface
{
    protected $name = "product";
    protected $key = "idProduct";
    protected $attributes = [
        "idProduct",
        "disponibility",
        "conditionProduct",
        "date",
        "isDelivered",
        "idModel",
        "idWarehouse",
        "idOffer",
        "idCart"
    ];
    protected $foreignKey = [
        "idModel" => ["product_model", "modelName"],
        "idWarehouse" => ["warehouse", "location"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO product(disponibility, conditionProduct, isDelivered, idModel, idWarehouse, idOffer, idCart) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->execute([
            $args["disponibility"],
            $args["conditionProduct"],
            $args["isDelivered"],
            $args["idModel"],
            $args["idWarehouse"],
            $args["idOffer"],
            $args["idCart"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function read_import_by_date_and_warehouse($day, $id): array
    {
        $query = $this->conn->prepare("SELECT idProduct,conditionProduct,product_model.modelName,warehouse.location,idOffer
                                    FROM product
                                    INNER JOIN product_model ON product.idModel = product_model.idModel
                                    INNER JOIN warehouse ON product.idWarehouse = warehouse.idWarehouse
                                    WHERE DATE(date) = ? AND product.idWarehouse = ?");
        $query->execute([$day, $id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read_export_by_date_and_warehouse($day, $id): array
    {
        $query = $this->conn->prepare("SELECT idProduct,conditionProduct,product_model.modelName,warehouse.location,idOrder
                                    FROM product
                                    INNER JOIN product_model ON product.idModel = product_model.idModel
                                    INNER JOIN warehouse ON product.idWarehouse = warehouse.idWarehouse
                                    INNER JOIN `order` on product.idCart = `order`.idCart
                                    WHERE DATE(orderDate) = ? AND product.idWarehouse = ?");
        $query->execute([$day, $id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
