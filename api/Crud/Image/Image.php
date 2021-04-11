<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Image extends CrudClass implements CrudInterface
{
    protected $name = "image";
    protected $key = "idImage";
    protected $attributes = [
        "idImage",
        "path",
        "idProduct"
    ];
    protected $foreignKey = [
        "idProduct" => ["product", "idProduct"]
    ];

    public function __construct(PDO $db, string $fileType)
    {
        parent::__construct($db);
        $this->fileType = $fileType;
    }

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO image(path, idProduct) VALUES (?, ?)");
        $query->execute([
            $args["path"],
            $args["idProduct"]
        ]);
    }
}
