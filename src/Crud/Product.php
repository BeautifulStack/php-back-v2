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
        $args = $this->check_attributes_create($args, $this->attributes, $this->key, ["date", "idCart"]);

        $query = $this->conn->prepare("INSERT INTO product(disponibility, conditionProduct, isDelivered, idModel, idWarehouse, idOffer) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([
            $args["disponibility"],
            $args["conditionProduct"],
            $args["isDelivered"],
            $args["idModel"],
            $args["idWarehouse"],
            $args["idOffer"]
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
                                    WHERE DATE(date) = ? AND product.idWarehouse = ? AND disponibility = 1");
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
                                    WHERE DATE(orderDate) = ? AND product.idWarehouse = ? AND disponibility = 0");
        $query->execute([$day, $id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function matchModelBrand(array $args)
    {
        $returnedValue = [];

        foreach ($args as $row) {
            $query = "SELECT officialPrice, brandName, logo, categoryName, path FROM product_model INNER JOIN brand ON product_model.idBrand = brand.idBrand INNER JOIN image ON image.idModel = product_model.idModel INNER JOIN category ON product_model.idCategory = category.idCategory WHERE modelName = \"".$row['product_modelmodelName']."\"";
            $query = $this->conn->query($query);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            $returnedValue[] = array_merge($row, $result[0]);
            // $retrunValue[] = array_push($row, $result);
        }
        
        return $returnedValue;
    }
}
