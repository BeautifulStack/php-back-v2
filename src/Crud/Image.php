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

        $args = $this->check_attributes_create($args, $this->attributes, $this->key, ["idModel"]);


        $query = $this->conn->prepare("INSERT INTO image(path, ".$id.") VALUES (?, ?)");
        $query->execute([
            $args["path"],
            $args["idRefer"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function read_by_id($id, $key): array
    {
        $query = $this->conn->prepare("SELECT path FROM image WHERE ".$key." = ?");
        $query->execute([$id]);

        return $query->fetchAll(PDO::FETCH_NUM);
    }

    public function delete_by_id($id, $key)
    {
        $query = $this->conn->prepare("DELETE FROM image WHERE ".$key." = ?");
        $query->execute([$id]);
    }
}