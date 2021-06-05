<?php

class Request
{
    public static function Prepare(string $query, array $values, PDO $conn)
    {
        $query = $conn->prepare($query);
        $isOk = $query->execute($values);

        if ($isOk) {
            return $query;
        } else {
            echo json_encode(['status' => 500, 'error' => 'Internal Error']);
            exit();
        }
    }

    public static function Last_Id(PDO $conn)
    {
        $query = $conn->query('SELECT LAST_INSERT_ID() as id');
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
