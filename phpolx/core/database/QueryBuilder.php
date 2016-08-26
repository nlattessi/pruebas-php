<?php

class QueryBuilder
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function selectAll($table)
    {
        $statement = $this->pdo->prepare("select * from {$table}");

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    public function selectById($table, $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$table} WHERE id = :id");

        $statement->execute([
            'id' => $id
        ]);

        return $statement->fetchAll(PDO::FETCH_CLASS);
    }
}
