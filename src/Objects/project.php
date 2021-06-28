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
        $projects = Request::Prepare('SELECT * FROM project WHERE active = 1', [], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

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

    public function getByUserId()
    {
        UserRights::UserAssoc($this->conn);

        $id = UserRights::UserInfo($this->conn);
        $projects = Request::Prepare('SELECT * FROM project WHERE idUser = ?', [$id], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'projects' => $projects]);
    }

    public function create()

    {
        UserRights::UserAssoc($this->conn);
        if (!isset($_POST['name'])) return json_encode(['status' => 400, 'error' => 'Please specify a name']);
        if (!isset($_POST['desc'])) return json_encode(['status' => 400, 'error' => 'Please specify a description']);
        if (!isset($_POST['goal'])) return json_encode(['status' => 400, 'error' => 'Please specify a objectif']);

        $id = UserRights::UserInfo($this->conn);

        Request::Prepare('INSERT INTO project ( name, description, objectif, idUser) VALUES (?, ?, ?, ?)', array(
            $_POST['name'],
            $_POST['desc'],
            $_POST['goal'],
            $id
        ), $this->conn);

        $project = Request::Last_Id($this->conn);

        $r = openssl_digest($project['id'], "RIPEMD160");

        Request::Prepare('UPDATE ' . $this->tableName . ' SET publicKey = ? WHERE idProject = ?', array(
            $r,
            $project['id'],
        ), $this->conn);

        return json_encode(['status' => 201, 'project' => $project['id']]);
    }

    public function deleteById(int $id)
    {
        UserRights::UserAssoc($this->conn);

        Request::Prepare('DELETE FROM project WHERE idProject = ?', [$id], $this->conn);
        return json_encode(['status' => 201]);
    }

    public function withdrawById(int $id)
    {
        UserRights::UserAssoc($this->conn);

        Request::Prepare('UPDATE ' . $this->tableName . ' SET active = ? WHERE idProject = ?', array(
            "0",
            $id,
        ), $this->conn);

        $bc = new BlockchainClient();
        $bc->new_withdraw(openssl_digest($id, "RIPEMD160"));
        return json_encode(['status' => 201]);
    }

    public function route(array $path)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($path[1]) && $path[1] == "User") return $this->getByUserId();
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($path[1]) && $path[1] !== "") return $this->getById($path[1]);
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($path[1]) && $path[1] !== "") return $this->deleteById($path[1]);
        if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($path[1]) && $path[1] !== "") return $this->withdrawById($path[1]);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
        else {
            return json_encode(['status' => 401, 'error' => 'No valid argument']);
        }
    }
}
