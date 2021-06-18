<?php

class Project
{
    protected $tableName = 'project';


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function get()
    {
        $projects = Request::Prepare('SELECT * FROM ' . $this->tableName, [], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'projects' => $projects]);
    }

    public function getById(int $id)
    {
        $project = Request::Prepare('SELECT * FROM ' . $this->tableName . ' WHERE idProject = ?', [$id], $this->conn)->fetch(PDO::FETCH_ASSOC);

        $bc = new BlockchainClient();
        $balance = $bc->get_balance($project['publicKey']);
        $project['balance'] = $balance;

        return json_encode(['status' => 201, 'project' => $project]);
    }

    public function create()

    {
        UserRights::UserAssoc($this->conn);
        if (!isset($_POST['name'])) return json_encode(['status' => 400, 'error' => 'Please specify a name']);
        if (!isset($_POST['description'])) return json_encode(['status' => 400, 'error' => 'Please specify a description']);
        if (!isset($_POST['objectif'])) return json_encode(['status' => 400, 'error' => 'Please specify a objectif']);



        Request::Prepare('INSERT INTO ' . $this->tableName . ' ( name, description, objectif) VALUES (?, ?)', array(
            $_POST['name'],
            $_POST['description'],
            $_POST['objectif']
        ), $this->conn);

        $project = Request::Last_Id($this->conn);

        $r = openssl_digest($project['id'], "RIPEMD160");

        Request::Prepare('UPDATE ' . $this->tableName . ' SET publicKey = ? WHERE idProject = ?', array(
            $r,
            $project['id'],
        ), $this->conn);

        return json_encode(['status' => 201, 'project' => $project['id']]);
    }

    public function route(array $path)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($path[1]) && $path[1] !== "") return $this->getById($path[1]);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
    }
}
