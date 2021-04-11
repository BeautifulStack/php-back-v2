<?php

class Image extends CrudClass implements CrudInterface
{
    protected $name = "image";
    protected $key = "idImage";
    protected $attributes = [
        "idImage",
        "path",
        "idRefer"
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

        $query = $this->conn->prepare("INSERT INTO image(path, idRefer) VALUES (?, ?); SELECT LAST_INSERT_ID() as id;");
        $query->execute([
            $args["path"],
            $args["idRefer"]
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
