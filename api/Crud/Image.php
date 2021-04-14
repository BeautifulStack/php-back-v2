<?php

class Image extends CrudClass implements CrudInterface
{
    protected $name = "image";
    protected $key = "idImage";
    protected $attributes = [
        "idImage",
        "path",
        "idModel",
        "idOffer"
    ];

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function create(array $args)
    {
        $id = $args["key"];
        unset($args["key"]);

        $args = $this->check_attributes_create($args, count($this->attributes)-2);


        $query = $this->conn->prepare("INSERT INTO image(path, ".$id.") VALUES (?, ?)");
        $query->execute([
            $args["path"],
            $args["idRefer"]
        ]);
        //return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function read_by_id($id, $key): array
    {
        $query = $this->conn->prepare("SELECT path FROM image WHERE ".$key." = ?");
        $query->execute([$id]);

        return $query->fetchAll(PDO::FETCH_NUM);
    }
}