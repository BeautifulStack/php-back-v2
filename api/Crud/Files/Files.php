<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class Files extends CrudClass implements CrudInterface
{
    protected $name = "file";
    protected $key = "idFile";
    protected $attributes = [
        "idFile",
        "filePath",
        "fileType",
        "idProduct",
        "idOrder",
        "idBrand",
        "idAssociation"
    ];
    private $fileType;

    public function __construct(PDO $db, string $fileType)
    {
        parent::__construct($db);
        $this->fileType = $fileType;
    }

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO files(filePath ,fileType, ".$this->fileType.") VALUES (?, ?, ?)");
        $query->execute([
            $args["filePath"],
            $args["fileType"],
            $args[$this->fileType]
        ]);
    }
}
