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
        $brands = Request::Prepare('SELECT * FROM ' . $this->tableName, [], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'projects' => $brands]);
    }

    public function create()

    {
        UserRights::UserAdmin($this->conn);
        if (!isset($_POST['name'])) return json_encode(['status' => 400, 'error' => 'Please specify a name']);
        if (!isset($_POST['description'])) return json_encode(['status' => 400, 'error' => 'Please specify a description']);



        Request::Prepare('INSERT INTO ' . $this->tableName . ' ( name, description) VALUES (?, ?)', array(
            $_POST['name'],
            $_POST['description'],

        ), $this->conn);

        $project = Request::Last_Id($this->conn);

        $r = openssl_digest($project['id'], "RIPEMD160");

        Request::Prepare('UPDATE ' . $this->tableName . ' SET publicKey = ? WHERE idProject = ?', array(
            $r,
            $project['id'],
        ), $this->conn);

        return json_encode(['status' => 201, 'project' => $project['id']]);
    }

    public function route()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
    }
}
