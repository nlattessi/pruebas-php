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

    public function create($table, $data)
    {
        $columns = implode(',', array_keys($data));
        $values = implode(',', array_fill(0, count($data), '?'));

        $statement = $this->pdo->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$values})");

        $statement->execute(
            array_values($data)
        );

        $id = $this->pdo->lastInsertId();

        return $this->selectById($table, $id);
    }
}
