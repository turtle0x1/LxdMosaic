<?php

namespace dhope0000\LXDClient\Model\Search\Index;

use dhope0000\LXDClient\Model\Database\Database;

class InsertIndex
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(array $index)
    {
        $sql = 'INSERT INTO `Search_Index` (
                    `SI_Data`
                ) VALUES (
                    :data
                )
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':data' => json_encode($index),
        ]);
        return $this->database->lastInsertId();
    }
}
